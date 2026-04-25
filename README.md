# Symfony app

Template for PHP/Symfony apps.

## Introduction

This template serves as a starting point when creating a new PHP/Symfony app.
To use this template for a new app, click on the
[Use this template](https://github.com/new?template_name=template-symfony-app&template_owner=robmeijer)
button on the template GitHub page.

## Template Structure

The structure of this template is as follows:

- `.github` - The GitHub configuration directory contains workflows, PR templates, Code Owners, etc.
- `app` - The app directory contains a standard Symfony project with application files only.
- `docker` - The Docker directory contains the app Dockerfile, entrypoint files, etc.
- `.dockerignore` - The file ignores everything by default, with a list of files and directories accessible to Docker.
- `.env.dist` - Environment variables used by Docker Compose, which can be overridden.
- `.gitignore` - The git ignore file.
- `CLAUDE.md` - This file provides guidance to Claude Code when working with code in this repository.
- `compose.override.yaml` - The Docker Compose file with configuration and overrides for local development.
- `compose.remote.yaml` - The Docker Compose file used to build the production-ready app Docker image.
- `compose.yaml` - The Docker Compose file used by default. Can be combined with other Compose files.
- `fly.toml` - The configuration file for deployment to `Fly.io`.
- `README.md` - This file.

### The Dockerfile

The app `Dockerfile` uses
[Docker's multi-stage functionality](https://docs.docker.com/build/building/multi-stage/) to optimise the build process.

The App stage, named `app` by default, contains the instructions necessary to build the production-ready image.

The Local stage, named `local` by default, extends the `app` stage and only contains the instructions and overrides
necessary for local development.

## Local Usage

Follow these steps to get the Symfony app running locally.

### Start the service

Run the following command to start the Symfony app.

```shell
docker compose up --build --wait
```

Once started, it can be accessed on https://localhost. Make sure to accept the certificate in your browser if necessary.

### Local environment variables

The local environment variables can be overridden by copying `.env.dist` to `.env` and providing new values.
The [Xdebug documentation](https://xdebug.org/docs/all_settings#mode) has a list of the available values for
`XDEBUG_MODE`, which is set to `off` by default.

Once the `.env` file has been created and updated with the appropriate values, rebuild and recreate the service for the
changes to take effect.

Alternatively, the environment variables can also be passed via the commandline, e.g.

```shell
XDEBUG_MODE=debug docker compose up --build --wait
```

This will install Xdebug, and enable step debugging.

### Execute commands in the container

Docker Compose can be used to execute commands inside the container using the service name.

For example, PHP-CS-Fixer can be run as follows:

```shell
docker compose exec app composer lint
```
