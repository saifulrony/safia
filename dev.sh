#!/bin/bash

# ==========================================
# WordPress Development Environment Starter
# ==========================================
# This script starts all services needed for WordPress development:
# - MySQL Database
# - phpMyAdmin (if available)
# - WordPress on port 7000
# ==========================================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration
WORDPRESS_DIR="/home/saiful/wordpress"
WORDPRESS_PORT="7000"
DB_NAME="wordpress_db"
DB_USER="wordpress_user"
DB_PASS="wordpress_password_123"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[$(date '+%H:%M:%S')]${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_header() {
    echo ""
    echo -e "${PURPLE}=========================================="
    echo "🚀 WordPress Development Environment"
    echo "==========================================${NC}"
    echo ""
}

# Function to check if a service is running
check_service() {
    systemctl is-active --quiet $1
}

# Function to start a service
start_service() {
    print_status "Starting $1..."
    sudo systemctl start $1
    if [ $? -eq 0 ]; then
        print_success "$1 started successfully"
        return 0
    else
        print_error "Failed to start $1"
        return 1
    fi
}

# Function to check if port is in use
check_port() {
    lsof -i :$1 >/dev/null 2>&1
}

# Function to kill process on port
kill_port() {
    local port=$1
    local pid=$(lsof -ti :$port)
    if [ ! -z "$pid" ]; then
        print_warning "Killing existing process on port $port (PID: $pid)"
        kill -9 $pid 2>/dev/null
        sleep 2
    fi
}

# Function to test database connection
test_database() {
    mysql -u $DB_USER -p$DB_PASS -e "USE $DB_NAME; SELECT 'Connection successful!' AS Status;" 2>/dev/null
    return $?
}

# Function to create database if it doesn't exist
create_database() {
    print_status "Ensuring database exists..."
    sudo mysql << EOF 2>/dev/null
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF
}

# Function to setup phpMyAdmin symlink
setup_phpmyadmin() {
    if [ -d "/usr/share/phpmyadmin" ]; then
        if [ ! -L "$WORDPRESS_DIR/phpmyadmin" ]; then
            print_status "Creating phpMyAdmin symlink..."
            ln -s /usr/share/phpmyadmin $WORDPRESS_DIR/phpmyadmin
            print_success "phpMyAdmin symlink created"
        else
            print_success "phpMyAdmin symlink already exists"
        fi
        return 0
    else
        print_warning "phpMyAdmin not installed"
        return 1
    fi
}

# Function to start WordPress development server
start_wordpress() {
    print_status "Starting WordPress development server on port $WORDPRESS_PORT..."
    
    # Kill any existing PHP server on the port
    kill_port $WORDPRESS_PORT
    
    # Change to WordPress directory
    cd $WORDPRESS_DIR
    
    # Start PHP development server in background (accessible from network)
    nohup php -S 0.0.0.0:$WORDPRESS_PORT > /dev/null 2>&1 &
    
    # Wait a moment for server to start
    sleep 3
    
    # Check if server is running
    if check_port $WORDPRESS_PORT; then
        print_success "WordPress server started on port $WORDPRESS_PORT"
        return 0
    else
        print_error "Failed to start WordPress server"
        return 1
    fi
}

# Function to display access information
show_access_info() {
    # Get local IP address
    LOCAL_IP=$(hostname -I | awk '{print $1}')
    
    echo ""
    echo -e "${GREEN}=========================================="
    echo "🎉 Development Environment Ready!"
    echo "==========================================${NC}"
    echo ""
    echo -e "${CYAN}📱 Access Your WordPress Site:${NC}"
    echo -e "   ${GREEN}→ http://localhost:$WORDPRESS_PORT${NC} (local)"
    echo -e "   ${GREEN}→ http://$LOCAL_IP:$WORDPRESS_PORT${NC} (network)"
    echo ""
    echo -e "${CYAN}🔧 WordPress Admin:${NC}"
    echo -e "   ${GREEN}→ http://localhost:$WORDPRESS_PORT/wp-admin${NC} (local)"
    echo -e "   ${GREEN}→ http://$LOCAL_IP:$WORDPRESS_PORT/wp-admin${NC} (network)"
    echo ""
    if [ -d "/usr/share/phpmyadmin" ]; then
        echo -e "${CYAN}🗄️  phpMyAdmin Database Manager:${NC}"
        echo -e "   ${GREEN}→ http://localhost:$WORDPRESS_PORT/phpmyadmin${NC} (local)"
        echo -e "   ${GREEN}→ http://$LOCAL_IP:$WORDPRESS_PORT/phpmyadmin${NC} (network)"
        echo ""
        echo -e "${YELLOW}Database Credentials:${NC}"
        echo -e "   Username: ${GREEN}$DB_USER${NC}"
        echo -e "   Password: ${GREEN}$DB_PASS${NC}"
        echo -e "   Database: ${GREEN}$DB_NAME${NC}"
        echo ""
    fi
    echo -e "${CYAN}📊 Service Status:${NC}"
    if check_service mysql; then
        echo -e "   MySQL: ${GREEN}✓ Running${NC}"
    else
        echo -e "   MySQL: ${RED}✗ Not Running${NC}"
    fi
    
    if check_service apache2; then
        echo -e "   Apache2: ${GREEN}✓ Running${NC}"
    else
        echo -e "   Apache2: ${YELLOW}⚠ Not Running (optional)${NC}"
    fi
    
    if check_port $WORDPRESS_PORT; then
        echo -e "   WordPress Server: ${GREEN}✓ Running on port $WORDPRESS_PORT${NC}"
    else
        echo -e "   WordPress Server: ${RED}✗ Not Running${NC}"
    fi
    echo ""
    echo -e "${YELLOW}💡 Useful Commands:${NC}"
    echo -e "   Stop all: ${CYAN}./dev.sh stop${NC}"
    echo -e "   Restart: ${CYAN}./dev.sh restart${NC}"
    echo -e "   Status: ${CYAN}./dev.sh status${NC}"
    echo -e "   Logs: ${CYAN}tail -f $WORDPRESS_DIR/wp-content/debug.log${NC}"
    echo ""
    echo -e "${PURPLE}Press Ctrl+C to stop the development server${NC}"
    echo -e "${PURPLE}==========================================${NC}"
    echo ""
}

# Function to stop all services
stop_services() {
    print_status "Stopping WordPress development server..."
    kill_port $WORDPRESS_PORT
    print_success "WordPress server stopped"
    
    print_status "Stopping MySQL..."
    sudo systemctl stop mysql
    print_success "MySQL stopped"
    
    print_status "Stopping Apache2..."
    sudo systemctl stop apache2
    print_success "Apache2 stopped"
}

# Function to show status
show_status() {
    echo ""
    echo -e "${BLUE}=========================================="
    echo "📊 Service Status"
    echo "==========================================${NC}"
    echo ""
    
    # Check MySQL
    if check_service mysql; then
        print_success "MySQL: Running"
    else
        print_error "MySQL: Not Running"
    fi
    
    # Check Apache2
    if check_service apache2; then
        print_success "Apache2: Running"
    else
        print_warning "Apache2: Not Running"
    fi
    
    # Check WordPress server
    if check_port $WORDPRESS_PORT; then
        print_success "WordPress Server: Running on port $WORDPRESS_PORT"
    else
        print_error "WordPress Server: Not Running"
    fi
    
    # Check phpMyAdmin
    if [ -d "/usr/share/phpmyadmin" ]; then
        print_success "phpMyAdmin: Available"
    else
        print_warning "phpMyAdmin: Not Installed"
    fi
    
    echo ""
}

# Function to restart services
restart_services() {
    print_header
    print_status "Restarting all services..."
    stop_services
    sleep 3
    start_all_services
}

# Main function to start all services
start_all_services() {
    print_header
    
    # Step 1: Start MySQL
    print_status "Step 1/5: Starting MySQL..."
    if check_service mysql; then
        print_success "MySQL is already running"
    else
        if start_service mysql; then
            sleep 2
        else
            print_error "Cannot start MySQL. Please check your installation."
            exit 1
        fi
    fi
    
    # Step 2: Create/verify database
    print_status "Step 2/5: Setting up database..."
    create_database
    if test_database; then
        print_success "Database connection verified"
    else
        print_warning "Database connection failed, but continuing..."
    fi
    
    # Step 3: Setup phpMyAdmin
    print_status "Step 3/5: Setting up phpMyAdmin..."
    setup_phpmyadmin
    
    # Step 4: Start Apache2 (optional)
    print_status "Step 4/5: Starting Apache2 (optional)..."
    if check_service apache2; then
        print_success "Apache2 is already running"
    else
        if start_service apache2; then
            print_success "Apache2 started"
        else
            print_warning "Apache2 failed to start (this is optional for development)"
        fi
    fi
    
    # Step 5: Start WordPress
    print_status "Step 5/5: Starting WordPress development server..."
    if start_wordpress; then
        show_access_info
        
        # Keep script running and show logs
        print_status "Monitoring WordPress server... (Press Ctrl+C to stop)"
        while true; do
            if ! check_port $WORDPRESS_PORT; then
                print_error "WordPress server stopped unexpectedly"
                break
            fi
            sleep 5
        done
    else
        print_error "Failed to start WordPress development server"
        exit 1
    fi
}

# Handle command line arguments
case "${1:-start}" in
    "start")
        start_all_services
        ;;
    "stop")
        print_header
        stop_services
        print_success "All services stopped"
        ;;
    "restart")
        restart_services
        ;;
    "status")
        show_status
        ;;
    "help"|"-h"|"--help")
        echo ""
        echo -e "${PURPLE}WordPress Development Environment Manager${NC}"
        echo ""
        echo -e "${CYAN}Usage:${NC}"
        echo "  ./dev.sh [command]"
        echo ""
        echo -e "${CYAN}Commands:${NC}"
        echo "  start     Start all services (default)"
        echo "  stop      Stop all services"
        echo "  restart   Restart all services"
        echo "  status    Show service status"
        echo "  help      Show this help message"
        echo ""
        echo -e "${CYAN}Services:${NC}"
        echo "  • MySQL Database (wordpress_db)"
        echo "  • phpMyAdmin (if installed)"
        echo "  • WordPress Development Server (port $WORDPRESS_PORT)"
        echo "  • Apache2 (optional)"
        echo ""
        ;;
    *)
        print_error "Unknown command: $1"
        echo "Use './dev.sh help' for usage information"
        exit 1
        ;;
esac
