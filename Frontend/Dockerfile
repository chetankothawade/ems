# Use lightweight Node image
FROM node:20-alpine

# Working directory inside container
WORKDIR /app

# Copy only package files first (better cache)
COPY package*.json ./

# Install deps
RUN npm install

# Copy rest of project
COPY . .

# Expose Vite port
EXPOSE 5173

# Start Vite dev server
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
