/// <reference types="vitest/globals" />
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import { store } from '../../app/store';
import StudentDashboard from './StudentDashboard';
import { ExamAPI } from '../../api/exam.api';
import { AttemptAPI } from '../../api/attempt.api';
import { vi } from 'vitest';

// Mock the APIs
vi.mock('../../api/exam.api', () => ({
  ExamAPI: {
    dashboard: vi.fn(),
  },
}));

vi.mock('../../api/attempt.api', () => ({
  AttemptAPI: {
    start: vi.fn(),
    submit: vi.fn(),
  },
}));

const mockExams = [
  {
    id: '1',
    title: 'Exam 1',
    max_attempts: 3,
    attempts_remaining: 2,
    cooldown_minutes: 10,
    can_start: true,
    attempts: [],
  },
  {
    id: '2',
    title: 'Exam 2',
    max_attempts: 2,
    attempts_remaining: 0,
    cooldown_minutes: 15,
    can_start: false,
    message: 'Cooldown active',
    attempts: [],
  },
];

describe('StudentDashboard', () => {
  beforeEach(() => {
    (ExamAPI.dashboard as any).mockResolvedValue({ data: mockExams });
    (AttemptAPI.start as any).mockResolvedValue({});
    (AttemptAPI.submit as any).mockResolvedValue({});
  });

  afterEach(() => {
    vi.clearAllMocks();
  });

  test('renders exam dashboard correctly', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <StudentDashboard />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      expect(screen.getByText('My Exams')).toBeTruthy();
      expect(screen.getByText('Exam 1')).toBeTruthy();
      expect(screen.getByText('Exam 2')).toBeTruthy();
    });
  });

  test('starts an exam attempt', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <StudentDashboard />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      expect(screen.getByText('Start Attempt')).toBeTruthy();
    });

    fireEvent.click(screen.getByText('Start Attempt'));

    await waitFor(() => {
      expect(AttemptAPI.start).toHaveBeenCalledWith('1');
    });
  });

  test('submits an exam attempt', async () => {
    const examsWithInProgress = [
      {
        ...mockExams[0],
        in_progress_attempt_id: 'attempt1',
      },
    ];

    (ExamAPI.dashboard as any).mockResolvedValue({ data: examsWithInProgress });

    render(
      <Provider store={store}>
        <BrowserRouter>
          <StudentDashboard />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      expect(screen.getByText('Submit Attempt')).toBeTruthy();
    });

    fireEvent.click(screen.getByText('Submit Attempt'));

    await waitFor(() => {
      expect(AttemptAPI.submit).toHaveBeenCalledWith('attempt1');
    });
  });
});
