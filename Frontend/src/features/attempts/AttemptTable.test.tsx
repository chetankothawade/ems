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

  test('formats time correctly for student (local time)', () => {
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
    // For student, should show local time, not UTC
    // The exact format depends on browser locale, but should NOT end with "UTC"
    const cells = screen.getAllByText((content, element) => {
      return element && element.tagName === 'TD' && content.includes('2023');
    });
    expect(cells.length).toBeGreaterThan(0);
    // Verify it's NOT showing UTC format for student
    expect(screen.queryByText(/UTC/)).not.toBeInTheDocument();
    expect(screen.getByText('-')).toBeInTheDocument(); // for completed_at null
  });

  test('formats time correctly for admin (UTC time)', () => {
    const attempts = [
      {
        id: 1,
        attempt_number: 1,
        status: 'completed',
        started_at: '2023-01-01T10:00:00Z',
        completed_at: null
      }
    ];
    render(<AttemptTable attempts={attempts} isAdmin={true} />);
    // For admin, should show UTC time
    expect(screen.getByText('2023-01-01 10:00:00.000 UTC')).toBeInTheDocument();
    expect(screen.getByText('-')).toBeInTheDocument(); // for completed_at null
  });
});