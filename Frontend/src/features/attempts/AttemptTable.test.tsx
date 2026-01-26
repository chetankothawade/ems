import '@testing-library/jest-dom';
import { render, screen } from '@testing-library/react';
import AttemptTable from './AttemptTable';

describe('AttemptTable', () => {
  test('renders table with no attempts', () => {
    render(<AttemptTable attempts={[]} />);
    expect(screen.getByText('No attempts found')).toBeInTheDocument();
  });

  test('renders table with attempts for student', () => {
    const attempts = [
      {
        id: 1,
        attempt_number: 1,
        status: 'completed',
        started_at: '2023-01-01T10:00:00Z',
        completed_at: '2023-01-01T11:00:00Z'
      }
    ];
    render(<AttemptTable attempts={attempts} />);
    expect(screen.getByText('1')).toBeInTheDocument();
    expect(screen.getByText('completed')).toBeInTheDocument();
    // Check that Attempt Id column is not present for student
    expect(screen.queryByText('Attempt Id')).not.toBeInTheDocument();
  });

  test('renders table with attempts for admin', () => {
    const attempts = [
      {
        id: 1,
        attempt_number: 1,
        status: 'completed',
        started_at: '2023-01-01T10:00:00Z',
        completed_at: '2023-01-01T11:00:00Z'
      }
    ];
    render(<AttemptTable attempts={attempts} isAdmin={true} />);
    expect(screen.getByText('Attempt Id')).toBeInTheDocument();
    expect(screen.getAllByText('1')).toHaveLength(2); // id and attempt_number
    expect(screen.getByText('completed')).toBeInTheDocument();
  });

  test('formats time correctly', () => {
    const attempts = [
      {
        id: 1,
        attempt_number: 1,
        status: 'completed',
        started_at: '2023-01-01T10:00:00Z',
        completed_at: null
      }
    ];
    render(<AttemptTable attempts={attempts} />);
    // For student, should show localized time
    expect(screen.getByText(/01\/01\/2023/)).toBeInTheDocument();
    expect(screen.getByText('-')).toBeInTheDocument(); // for completed_at null
  });
});