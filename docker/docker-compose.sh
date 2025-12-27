#!/bin/bash

export PROJECT_PATH="$(realpath $(dirname $0)/..)"

PROJECT_NAME="${PROJECT_NAME:-$(basename $PROJECT_PATH)}"

APP_ENV="${APP_ENV:-dev}"

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yml}"

COMPOSE_FILE_PATH="$(realpath $(dirname $0))/$COMPOSE_FILE"

if command -v docker-compose &> /dev/null;
then
  docker-compose -p "$PROJECT_NAME-$APP_ENV" -f $COMPOSE_FILE_PATH "${@}"
else
  docker compose -p "$PROJECT_NAME-$APP_ENV" -f $COMPOSE_FILE_PATH "${@}"
fi
