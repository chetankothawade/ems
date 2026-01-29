# EMS Frontend

A modern React-based frontend application for the Exam Management System (EMS). This application provides interfaces for students to take exams and for administrators to manage exams, view attempts, and oversee the system.

## Features

- **Student Dashboard**: View available exams, start attempts, and track progress
- **Admin Panel**: Create and manage exams, view all attempts, and monitor system activity

## Tech Stack

- **Frontend Framework**: React 19 with TypeScript
- **Build Tool**: Vite 6
- **State Management**: Redux Toolkit
- **UI Library**: React Bootstrap with Bootstrap 5.3
- **HTTP Client**: Axios
- **Routing**: React Router DOM 7
- **Form Handling**: React Hook Form with Zod validation
- **Testing**: Vitest with React Testing Library
- **Notifications**: React Hot Toast

## Prerequisites

### For Local Development
- **Node.js**: Version 16 or higher (tested with v20)
- **npm**: Comes with Node.js
- **Backend API**: Must be running on `http://localhost:8000`

### For Docker
- **Docker**: Latest stable version
- **Docker Compose**: For orchestrating services (optional)

## Local Setup

### 1. Clone the Repository
```bash
git clone <repository-url>
cd Frontend
```

### 2. Install Dependencies
```bash
npm install
```

### 3. Environment Configuration
Ensure your backend API is running on `http://localhost:8000`. The application expects the following endpoints:
- `/student` - Student-related endpoints
- `/admin` - Admin-related endpoints

### 4. Start Development Server
```bash
npm run dev
```

The application will be available at `http://localhost:3030`. The Vite config proxies API requests to `http://localhost:8000`.

### 5. Verify Installation
Open your browser and navigate to the application URL. You should see the login page or dashboard.

## Docker Setup

### Option 1: Using Docker Directly

#### Build the Docker Image
```bash
docker build -t ems-frontend:latest .
```

#### Run the Container
```bash
docker run -p 3000:5173 --name ems-frontend ems-frontend:latest
```

The application will be accessible at `http://localhost:3000`.

#### Stop the Container
```bash
docker stop ems-frontend
docker rm ems-frontend
```

### Option 2: Using Docker Compose (Recommended)

If you have a `docker-compose.yml` file in the parent directory:

#### Build and Start Services
```bash
docker compose up --build
```

#### View Logs
```bash
docker compose logs -f frontend
```

#### Run Tests in Docker
```bash
docker compose exec frontend npm test
```

#### Stop Services
```bash
docker compose down
```

### Docker Configuration Details

- **Base Image**: Node 20 Alpine (lightweight, ~170MB)
- **Working Directory**: `/app` inside the container
- **Exposed Port**: 5173 (internal container port)
- **Startup Command**: `npm run dev -- --host 0.0.0.0`
  - The `--host 0.0.0.0` flag allows access from outside the container
  - Accessible externally on port 3000 (as per docker-compose.yml)

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

### Development
```bash
npm run dev        # Start Vite development server with HMR
npm run build      # Build optimized production bundle
npm run preview    # Preview production build locally
```

### Testing
```bash
npm run test       # Run Vitest test suite in watch mode
npm run test:ui    # Run tests with UI (if configured
```

### Building for Production
```bash
npm run build      # Outputs to dist/ directory
npm run preview    # Preview the production build
```

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

The frontend communicates with a backend REST API for the following operations:

### Exam Management
- Fetch available exams for students
- Create, update exams (admin only)
- Retrieve exam details

### Attempt Tracking
- Create exam attempts
- Submit answers
- Retrieve attempt history

### Expected API Base URL
```
http://localhost:8000
```

The Axios instance is configured in `src/api/http.ts` and can be updated if the API endpoint changes.

### API Error Handling
The application includes error handling for:
- Network failures
- Authentication errors (401, 403)
- Server errors (5xx)
- Validation errors (400)

## Testing

### Running Tests Locally
```bash
npm run test
```

This will start Vitest in watch mode. Tests will rerun when you save files.

### Running Tests in Docker
```bash
docker compose exec frontend npm test
```

### Test Files
Test files are located alongside their components with `.test.tsx` extension:
- `src/components/**/*.test.tsx`
- `src/features/**/*.test.tsx`

### Test Setup
- Configured in `src/test/setup.ts`
- Uses React Testing Library for component testing
- Includes Jest DOM matchers

## Environment Variables

Currently, the application uses hardcoded API endpoint (`http://localhost:8000`). For production deployment, you may want to add environment variables:

Then update `src/api/http.ts` to use the environment variable.

## Troubleshooting

### Port Already in Use
If port 3000 is already in use:
- **Local**: Vite will automatically try the next available port
- **Docker**: Run with a different port mapping: `docker run -p 8080:5173 ems-frontend`

### Backend Connection Issues
- Verify the backend is running on `http://localhost:8000`
- Check CORS configuration in the backend
- Review browser console for detailed error messages

### Docker Build Issues
- Clear Docker cache: `docker system prune`
- Rebuild without cache: `docker build --no-cache -t ems-frontend .`

### Module Not Found Errors
```bash
rm -rf node_modules package-lock.json
npm install
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
