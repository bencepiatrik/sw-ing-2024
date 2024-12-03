# Stage 1: Builder Stage
FROM node:18.14.2-alpine3.17 AS builder-stage

# Set working directory
WORKDIR /usr/src/frontend

# Ensure node_modules directory has correct permissions
RUN mkdir -p node_modules && chmod -R 777 node_modules

# Copy package.json and package-lock.json for dependency installation
COPY ./frontend/package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the frontend source code
COPY ./frontend .

# Build the production-ready assets (optional, depending on your needs)
RUN npm run build

# Stage 2: Final Image
FROM node:18.14.2-alpine3.17

# Set a non-root user for security
USER node

# Set working directory
WORKDIR /usr/src/frontend

# Copy built artifacts and installed node_modules from the builder stage
COPY --chown=node:node --from=builder-stage /usr/src/frontend .

# Set appropriate permissions
RUN chmod -R 777 node_modules

# Start the development server
CMD ["npm", "run", "dev"]
