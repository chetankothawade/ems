# EMS Frontend

A modern React-based frontend application for the Exam Management System (EMS). This application provides interfaces for students to take exams and for administrators to manage exams, view attempts, and oversee the system.

## Features

- **Student Dashboard**: View available exams, start attempts, and track progress
- **Admin Panel**: Create and manage exams, view all attempts, and monitor system activity


## Tech Stack

- **Frontend Framework**: React 18 with TypeScript
- **Build Tool**: Vite
- **State Management**: Redux Toolkit
- **UI Library**: React Bootstrap
- **HTTP Client**: Axios (via custom http.ts)
- **Routing**: React Router

## Prerequisites

- Node.js (version 16 or higher)
- npm or yarn
- Backend API server running on `http://localhost:8080`

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd ems-frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm run dev
   ```

4. Open [http://localhost:3030](http://localhost:3030) in your browser

## Usage

### For Students
- Browse available exams
- Start exam attempts
- View attempt history and results

### For Administrators
- Create new exams using the exam form
- View and manage all exam attempts
- Monitor system usage

## Available Scripts

- `npm run dev` - Start the Vite development server
- `npm run build` - Build the app for production
- `npm run preview` - Preview the production build locally
- `npm run test` - Run the test suite

## Using Docker

To run the application using Docker:

### Prerequisites
- Docker installed on your system

### Build the Docker image
```bash
docker build -t ems-frontend .
```

### Run the container
```bash
docker run -p 3030:3030 ems-frontend
```

Open [http://localhost:3030](http://localhost:3030) to view it in your browser.

## Project Structure

```
src/
├── api/           # API service functions
├── app/           # Redux store and slices
├── components/    # Reusable UI components
├── features/      # Feature-specific components
├── hooks/         # Custom React hooks
├── pages/         # Page components
├── routes/        # Routing configuration
└── styles/        # Global styles
```

## API Integration

This frontend communicates with a backend API for:
- User authentication
- Exam management
- Attempt tracking
- Data persistence

The backend should be running on `http://localhost:8080` with endpoints for `/student` and `/admin` routes.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is licensed under the MIT License.
