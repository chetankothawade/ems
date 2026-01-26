import { Button } from "react-bootstrap";
import { AttemptAPI } from "../../api/attempt.api";

interface Props {
  examId: string;
  attempt?: any;
  canStart: boolean;
  message?: string;
  onRefresh?: () => void;
}

export default function AttemptActions({
  examId,
  attempt,
  canStart,
  message,
  onRefresh
}: Props) {
  const start = async () => {
    await AttemptAPI.start(examId);
    onRefresh?.();
  };

  const submit = async () => {
    await AttemptAPI.submit(attempt.id);
    onRefresh?.();
  };

  /*
  |--------------------------------------------------------------------------
  | RULES ENGINE (strict states)
  |--------------------------------------------------------------------------
  */

  // In progress → only submit allowed
  if (attempt?.status === "in_progress") {
    return (
      <Button variant="success" onClick={submit}>
        Submit Attempt
      </Button>
    );
  }

  // Can start
  if (canStart) {
    return (
      <Button variant="primary" onClick={start}>
        Start Attempt
      </Button>
    );
  }

  // Blocked
  return <span className="text-danger">{message || "Not allowed"}</span>;
}
