import { useEffect, useState } from "react";
import { ExamAPI } from "../../api/exam.api";
import { AttemptAPI } from "../../api/attempt.api";
import AttemptTable from "../../features/attempts/AttemptTable";
import { Card, Button, Container, Row, Col } from "react-bootstrap";
import toast from "react-hot-toast";

export default function StudentDashboard() {
  const [exams, setExams] = useState<any[]>([]);

  const load = () => ExamAPI.dashboard().then(r => setExams(r.data));

  useEffect(() => {
    load();
  }, []);

  const start = async (id: string) => {
    try {
      await AttemptAPI.start(id);
      load();
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Unable to start attempt");
    }
  };

  const submit = async (attemptId: string) => {
    try {
      await AttemptAPI.submit(attemptId);
      load();
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Unable to submit attempt");
    }
  };

  return (
    <Container className="py-4">
      <h4 className="mb-4">My Exams</h4>

      <Row>
        {exams.map(exam => {
          const inProgress = exam.in_progress_attempt_id;

          return (
            <Col md={12} key={exam.id} className="mb-3">
              <Card>
                <Card.Body>

                  {/* Title */}
                  <Card.Title className="mb-3">
                    {exam.title}
                  </Card.Title>

                  {/* Info */}
                  <div className="mb-3 small text-muted">
                    <div>Max Attempts: {exam.max_attempts}</div>
                    <div>Remaining: {exam.attempts_remaining}</div>
                    <div>Cooldown: {exam.cooldown_minutes} min</div>
                  </div>

                  {/* Button */}
                  {inProgress ? (
                    <Button
                      variant="success"
                      className="w-100 mb-3"
                      onClick={() => submit(inProgress)}
                    >
                      Submit Attempt
                    </Button>
                  ) : exam.can_start ? (
                    <Button
                      variant="primary"
                      className="w-100 mb-3"
                      onClick={() => start(exam.id)}
                    >
                      Start Attempt
                    </Button>
                  ) : (
                    <div className="text-danger small mb-3">
                      {exam.message}
                    </div>
                  )}

                  {/* History */}
                  <AttemptTable attempts={exam.attempts} />

                </Card.Body>
              </Card>
            </Col>
          );
        })}
      </Row>
    </Container>
  );
}
