import { Link } from "react-router-dom";
import { ButtonGroup, Button } from "react-bootstrap";

export default function ViewSwitcher() {
  return (
    <ButtonGroup className="mb-3">
      <Button as={Link} to="/admin">Admin</Button>
      <Button as={Link} to="/student">Student</Button>
    </ButtonGroup>
  );
}
