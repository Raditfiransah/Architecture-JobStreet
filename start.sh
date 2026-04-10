#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting PBL Architecture Project Development Environment..."

# Check if docker is installed
if ! [ -x "$(command -v docker)" ]; then
  echo 'Error: docker is not installed.' >&2
  exit 1
fi

# Build and start containers
echo "📦 Building and starting containers..."
docker compose up -d --build

echo "--------------------------------------------------------"
echo "✅ Environment is up and running!"
echo "--------------------------------------------------------"
echo "🌐 App URL:       http://localhost:8000"
echo "🗄️  phpMyAdmin:    http://localhost:8081"
echo "📊 Vite Dev:      http://localhost:5173"
echo "--------------------------------------------------------"
echo "🔍 Useful Commands:"
echo "  - View Logs:      docker compose logs -f"
echo "  - App Shell:      docker exec -it pbl-architecture-app sh"
echo "  - Stop:           docker compose down"
echo "--------------------------------------------------------"
echo "💡 Tip: You can also use 'make' commands for common tasks."
