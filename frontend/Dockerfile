FROM node:lts-alpine

# Set the working directory
WORKDIR /app

# Copy frontend dependencies to container
COPY package.json ./

# Install dependencies
RUN npm install
RUN npm cache clean --force

# Copy all application files
COPY . .

RUN rm -rf node_modules
RUN npm i

# Expose the port for the frontend
EXPOSE 3000

# Start the React app
CMD ["npm", "run", "dev"]
