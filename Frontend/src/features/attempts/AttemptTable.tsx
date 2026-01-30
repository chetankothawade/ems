import { Table } from "react-bootstrap";

export default function AttemptTable({
  attempts = [],
  isAdmin = false
}: {
  attempts?: any[];
  isAdmin?: boolean;
}) {

  const formatTime = (time: any) => {
    if (!time) return "-";

    // backend sends object { date: "" }
    const raw = typeof time === "object" ? time.date : time;

    const date = new Date(raw);
    if (isNaN(date.getTime())) return "-";

    // Admin: show UTC time, Student: show local browser time
    if (isAdmin) {
      // Display in UTC format as per admin requirements
      return date.toISOString().replace("T", " ").replace("Z", " UTC");
    } else {
      // Convert to local time using browser's timezone (per timezone localization guide)
      // toLocaleString() automatically handles DST and browser timezone
      return date.toLocaleString("en-US", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false
      });
    }
  };

  return (
    <Table striped bordered>
      <thead>
        <tr>
          {isAdmin && <th>Attempt Id</th>}
          <th>Attempt Number</th>
          <th>Status</th>
          <th>Start Time {isAdmin ? "(UTC)" : ""}</th>
          <th>End Time {isAdmin ? "(UTC)" : ""}</th>
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
              <td>{formatTime(a.started_at)}</td>
              <td>{formatTime(a.completed_at)}</td>
            </tr>
          ))
        )}
      </tbody>
    </Table>
  );
}
