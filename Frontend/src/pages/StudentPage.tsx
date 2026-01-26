import { Container, Row, Col, Card } from "react-bootstrap";
import StudentDashboard from "../features/exams/StudentDashboard";

export default function StudentPage() {
  return (
    <Container fluid>

      <Row className="mb-4">
        <Col>
          <h3 className="fw-bold">Student Dashboard</h3>
          <p className="text-muted mb-0">
            View your exams and manage attempts
          </p>
        </Col>
      </Row>

      <Row>
        <Col>
          <Card className="shadow-sm">
            <Card.Body>
              <StudentDashboard />
            </Card.Body>
          </Card>
        </Col>
      </Row>

    </Container>
  );
}
