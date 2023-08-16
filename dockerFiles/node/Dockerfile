# Use the official Node.js image as a base image
FROM node:18

# Set the working directory inside the container
WORKDIR /usr/src/app

# Copy package.json and package-lock.json to the working directory
COPY package*.json ./

# Install the app dependencies using npm
RUN npm install

# Copy the content of the local persistenceService directory to a directory in the container
COPY persistenceService/ ./persistenceService

# Specify the command to run on container start
CMD ["node", "persistenceService/index.js"]