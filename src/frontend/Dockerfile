# Use the official Node.js 18 image as the base image
FROM node:18

# Set the working directory in the container
WORKDIR /frontend

# Copy package.json and package-lock.json to the container
COPY package*.json ./

# COPY ENV variable
COPY .env ./

# COPY tsconfig.json file
COPY tsconfig.json ./
# Install app dependencies

RUN npm install

# Copy the rest of the app code to the container
COPY . .

RUN npx prisma migrate deploy

# Expose port 3333
EXPOSE 3333

# Start the app
CMD [ "npm", "start" ]