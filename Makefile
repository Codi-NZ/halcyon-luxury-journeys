.PHONY: help prod dev install setup clean update db-pull up health-check
.DEFAULT_GOAL := help

# Colors for output
CYAN := \033[0;36m
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
NC := \033[0m # No Color

## Help command - lists all available commands
help:
	@echo "$(CYAN)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'

## Check if DDEV is running, start if needed
health-check:
	@if ! ddev describe >/dev/null 2>&1; then \
		echo "$(YELLOW)DDEV is not running. Starting DDEV...$(NC)"; \
		ddev start; \
	else \
		echo "$(GREEN)✓ DDEV is already running$(NC)"; \
	fi

## Build for production
prod: health-check
	@echo "$(CYAN)Building for production...$(NC)"
	ddev exec npm run build
	@echo "$(GREEN)✓ Production build complete$(NC)"

## Start development server (quick start for existing projects)
dev: ## Start dev server (use this daily)
	@echo "$(CYAN)Checking DDEV status...$(NC)"
	@if ! ddev describe >/dev/null 2>&1; then \
		echo "$(YELLOW)Starting DDEV...$(NC)"; \
		ddev start; \
	elif ! ddev exec pwd >/dev/null 2>&1; then \
		echo "$(YELLOW)Containers not ready. Restarting DDEV...$(NC)"; \
		ddev restart; \
	fi
	@echo "$(CYAN)Starting development server...$(NC)"
	ddev exec npm run dev

## Full initial setup for new project
install: ## Initial project setup (first time only)
	@echo "$(CYAN)Starting initial project setup...$(NC)"
	ddev start
	@if [ -f .env ]; then \
		echo "$(YELLOW).env already exists, skipping copy$(NC)"; \
	else \
		cp .env.example .env && echo "$(GREEN)✓ Copied .env.example to .env$(NC)" || echo "$(RED)✗ Failed to copy .env$(NC)"; \
	fi
	ddev exec npm install
	ddev composer install
	ddev php craft setup/app-id
	ddev php craft setup/security-key
	@echo "" >> .env
	@echo "$(GREEN)✓ Install complete 🎉$(NC)"
	ddev launch

## Update project (git pull, dependencies, migrations)
setup: ## Daily update (git pull + deps + migrations)
	@echo "$(CYAN)Updating project...$(NC)"
	ddev auth ssh
	ddev exec git pull
	ddev exec npm install
	ddev composer update
	ddev exec php craft up --interactive=0
	@echo "$(GREEN)✓ Project updated$(NC)"

## Quick sync (just pull code and run migrations)
sync: health-check ## Quick sync (pull + migrations only)
	@echo "$(CYAN)Syncing project...$(NC)"
	ddev exec git pull
	ddev exec php craft up --interactive=0
	@echo "$(GREEN)✓ Project synced$(NC)"

## Run Craft migrations
up: health-check ## Run Craft CMS migrations
	@echo "$(CYAN)Running migrations...$(NC)"
	ddev exec php craft up --interactive=0
	@echo "$(GREEN)✓ Migrations complete$(NC)"

## Update Craft CMS and plugins
update: health-check ## Update Craft CMS and all plugins
	@echo "$(CYAN)Updating Craft CMS...$(NC)"
	ddev exec php craft update all
	@echo "$(GREEN)✓ Updates complete$(NC)"

## Pull database from remote
db-pull: health-check ## Pull database from remote environment
	@echo "$(CYAN)Pulling database...$(NC)"
	swiff-5 --databasePull
	@echo "$(GREEN)✓ Database pulled$(NC)"

## Clean cache and dependencies
clean: ## Remove vendor, node_modules, and clear caches
	@echo "$(CYAN)Cleaning project...$(NC)"
	rm -rf vendor/ node_modules/
	ddev composer clear-cache
	ddev exec npm cache clean --force
	@echo "$(GREEN)✓ Clean complete$(NC)"

## Fresh start - clean and reinstall everything
fresh: clean install ## Nuclear option: clean + full reinstall

## Stop DDEV
stop: ## Stop DDEV containers
	@echo "$(YELLOW)Stopping DDEV...$(NC)"
	ddev stop
	@echo "$(GREEN)✓ DDEV stopped$(NC)"

## Restart DDEV
restart: ## Restart DDEV containers
	@echo "$(CYAN)Restarting DDEV...$(NC)"
	ddev restart
	@echo "$(GREEN)✓ DDEV restarted$(NC)"

## Open project in browser
open: health-check ## Open project in browser
	ddev launch

## View DDEV logs
logs: ## Show DDEV logs
	ddev logs -f

## SSH into web container
ssh: health-check ## SSH into DDEV web container
	ddev ssh