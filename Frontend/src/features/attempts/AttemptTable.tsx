import { Table } from "react-bootstrap";

export default function AttemptTable({
  attempts = [],
  isAdmin = false
}: {
  attempts?: any[];
  isAdmin?: boolean;
}) {

  const formatUTC = (time: any) => {
    if (!time) return "-";

    // backend sends object { date: "" }
    const raw = typeof time === "object" ? time.date : time;

    const date = new Date(raw);
    if (isNaN(date.getTime())) return "-";

    // Always UTC
    return date.toISOString().replace("T", " ").replace("Z", " UTC");
  };

  return (
    <Table striped bordered>
      <thead>
        <tr>
          {isAdmin && <th>Attempt Id</th>}
          <th>Attempt Number</th>
          <th>Status</th>
          <th>Start Time (UTC)</th>
          <th>End Time (UTC)</th>
        </tr>
      </thead>

      <tbody>
        {attempts.length === 0 ? (
          <tr>
            <td colSpan={isAdmin ? 5 : 4} className="text-center text-muted">
              No attempts found
            </td>
          </tr>
        ) : (
          attempts.map((a: any) => (
            <tr key={a.id}>
              {isAdmin && <td>{a.id}</td>}
              <td>{a.attempt_number}</td>
              <td>{a.status}</td>
              <td>{formatUTC(a.started_at)}</td>
              <td>{formatUTC(a.completed_at)}</td>
            </tr>
          ))
        )}
      </tbody>
    </Table>
  );
}
