import { useEffect, useState } from "react";
import { Button, Table, Modal } from "react-bootstrap";
import ExamForm from "./ExamForm";
import { ExamAPI } from "../../api/exam.api";
import AttemptTable from "../attempts/AttemptTable";

export default function AdminExamList() {
  const [exams, setExams] = useState<any[]>([]);
  const [selected, setSelected] = useState<any>(null);
  const [showForm, setShowForm] = useState(false);
  const [attempts, setAttempts] = useState<any[]>([]);
  const [showHistory, setShowHistory] = useState(false);

  const load = async () => {
    // TODO: Use admin API for listing exams if available, currently reusing student dashboard
    const res = await ExamAPI.dashboard();
    setExams(res.data);
  };

  useEffect(() => {
    load();
  }, []);

  const openCreate = () => {
    setSelected(null);
    setShowForm(true);
  };

  const openEdit = (exam: any) => {
    setSelected(exam);
    setShowForm(true);
  };



  const viewAttempts = async (id: string, e?: any) => {
    e?.preventDefault();

    setShowHistory(true);   // mark history opened
    setAttempts([]);       // clear old

    const res = await ExamAPI.attempts(id);

    setAttempts(res.data || []);
  };


  return (
    <>
      <div className="d-flex justify-content-between mb-3">
        <h4>Exams</h4>
        <Button type="button" onClick={openCreate}>+ Create Exam</Button>
      </div>

      <Table striped bordered hover>
        <thead>
          <tr>
            <th>Title</th>
            <th>Max Attempts</th>
            <th>Cooldown</th>
            <th width="240">Actions</th>
          </tr>
        </thead>

        <tbody>
          {exams.map((exam) => (
            <tr key={exam.id}>
              <td>{exam.title}</td>
              <td>{exam.max_attempts}</td>
              <td>{exam.cooldown_minutes} min</td>

              <td className="d-flex gap-2">
                <Button
                  type="button"
                  size="sm"
                  variant="warning"
                  onClick={() => openEdit(exam)}
                >
                  Edit
                </Button>

                <Button
                  type="button"
                  size="sm"
                  variant="info"
                  onClick={() => viewAttempts(exam.id)}
                >

                  History
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      {/* Attempts history */}
      {showHistory && (
        <>
          <h5 className="mt-4">Attempt History</h5>

          {attempts.length > 0 ? (
            <AttemptTable attempts={attempts} isAdmin />
          ) : (
            <div className="text-muted">No attempts found for this exam</div>
          )}
        </>
      )}


      {/* Create / Update modal */}
      <Modal show={showForm} onHide={() => setShowForm(false)}>
        <Modal.Header closeButton>
          <Modal.Title>
            {selected ? "Update Exam" : "Create Exam"}
          </Modal.Title>
        </Modal.Header>

        <Modal.Body>
          <ExamForm
            exam={selected}
            onSave={() => {
              setShowForm(false);
              load();
            }}
          />
        </Modal.Body>
      </Modal>
    </>
  );
}
