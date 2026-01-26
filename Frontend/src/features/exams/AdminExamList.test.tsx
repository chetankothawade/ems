import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import { store } from '../../app/store';
import AdminExamList from './AdminExamList';
import { ExamAPI } from '../../api/exam.api';
import { vi } from 'vitest';

// Mock the API
vi.mock('../../api/exam.api', () => ({
  ExamAPI: {
    dashboard: vi.fn(),
    attempts: vi.fn(),
  },
}));

const mockExams = [
  { id: '1', title: 'Exam 1', max_attempts: 3, cooldown_minutes: 10 },
  { id: '2', title: 'Exam 2', max_attempts: 2, cooldown_minutes: 15 },
];

const mockAttempts = [
  { id: '1', score: 85, submitted_at: '2023-01-01' },
];

describe('AdminExamList', () => {
  beforeEach(() => {
    (ExamAPI.dashboard as any).mockResolvedValue({ data: mockExams });
    (ExamAPI.attempts as any).mockResolvedValue({ data: mockAttempts });
  });

  afterEach(() => {
    vi.clearAllMocks();
  });

  test('renders exam list correctly', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <AdminExamList />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      expect(screen.getByText('Exams')).toBeTruthy();
      expect(screen.getByText('Exam 1')).toBeTruthy();
      expect(screen.getByText('Exam 2')).toBeTruthy();
    });
  });

  test('opens create exam modal', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <AdminExamList />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      expect(screen.getByText('+ Create Exam')).toBeTruthy();
    });

    fireEvent.click(screen.getByText('+ Create Exam'));

    await waitFor(() => {
      expect(screen.getByText('Create Exam')).toBeTruthy();
    });
  });

  test('opens edit exam modal', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <AdminExamList />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      const editButtons = screen.getAllByText('Edit');
      expect(editButtons.length).toBe(2);
    });

    const editButtons = screen.getAllByText('Edit');
    fireEvent.click(editButtons[0]);

    await waitFor(() => {
      expect(screen.getByText('Update Exam')).toBeTruthy();
    });
  });

  test('views attempt history', async () => {
    render(
      <Provider store={store}>
        <BrowserRouter>
          <AdminExamList />
        </BrowserRouter>
      </Provider>
    );

    await waitFor(() => {
      const historyButtons = screen.getAllByText('History');
      expect(historyButtons.length).toBe(2);
    });

    const historyButtons = screen.getAllByText('History');
    fireEvent.click(historyButtons[0]);

    await waitFor(() => {
      expect(screen.getByText('Attempt History')).toBeTruthy();
      expect(ExamAPI.attempts).toHaveBeenCalledWith('1');
    });
  });
});
