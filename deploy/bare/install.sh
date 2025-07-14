#!/bin/bash

set -e

echo "Starting installation..."


SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
CHUNKS_DIR="$SCRIPT_DIR/deploy"


cd "$SCRIPT_DIR"


for step in "$CHUNKS_DIR"/*.sh; do
    step_name=$(basename "$step")
    echo ""
    echo "Running step: $step_name"
    bash "$step"
done

echo ""
echo "All installation steps completed!"
