import { Container, Row, Col, Card } from "react-bootstrap";
import AdminExamList from "../features/exams/AdminExamList";

export default function AdminPage() {
  return (
    <Container fluid>

      <Row className="mb-4">
        <Col>
          <h3 className="fw-bold">Admin Panel</h3>
          <p className="text-muted mb-0">
            Manage exams, rules and student attempts
          </p>
        </Col>
      </Row>

      <Row>
        <Col>
          <Card className="shadow-sm">
            <Card.Body>
              <AdminExamList />
            </Card.Body>
          </Card>
        </Col>
      </Row>

    </Container>
  );
}
