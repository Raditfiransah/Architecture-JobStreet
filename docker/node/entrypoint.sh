#!/bin/sh
set -e

# Install dependencies if node_modules is missing
if [ ! -d "node_modules" ]; then
    echo "node_modules not found. Running npm install..."
    npm install
else
    # Optionally run install to ensure sync, but it can be slow
    # npm install
    echo "node_modules found."
fi

echo "Starting Vite dev server..."
exec npm run dev -- --host
