import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { Button, Form } from "react-bootstrap";
import { ExamAPI } from "../../api/exam.api";

const schema = z.object({
  id: z.string().uuid(),
  title: z.string().min(1),
  max_attempts: z.number().min(1).max(1000),
  cooldown_minutes: z.number().min(0).max(525600)
});

type ExamFormData = z.infer<typeof schema>;

interface ExamFormProps {
  exam?: any;
  onSave: () => void;
}

export default function ExamForm({ exam, onSave }: ExamFormProps) {
  const isEdit = !!exam;

  const { register, handleSubmit, formState: { errors }, setValue } = useForm<ExamFormData>({
    resolver: zodResolver(schema),
    defaultValues: exam || {
      id: crypto.randomUUID(),
      title: "",
      max_attempts: 1,
      cooldown_minutes: 0
    }
  });

  const onSubmit = async (data: ExamFormData) => {
    if (isEdit) {
      await ExamAPI.update(data.id, data);
    } else {
      await ExamAPI.create(data);
    }
    onSave();
  };

  return (
    <Form onSubmit={handleSubmit(onSubmit)}>
      <Form.Group className="mb-3">
        <Form.Label>Exam ID</Form.Label>
        <Form.Control {...register("id")} readOnly={isEdit} />
        {errors.id && <p className="text-danger">Must be a valid GUID</p>}
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Title</Form.Label>
        <Form.Control {...register("title")} />
        {errors.title && <p className="text-danger">Required</p>}
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Max Attempts</Form.Label>
        <Form.Control
          type="number"
          {...register("max_attempts", { valueAsNumber: true })}
        />
        {errors.max_attempts && <p className="text-danger">Must be between 1 and 1000</p>}
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Cooldown Minutes</Form.Label>
        <Form.Control
          type="number"
          {...register("cooldown_minutes", { valueAsNumber: true })}
        />
        {errors.cooldown_minutes && <p className="text-danger">Must be between 0 and 525600</p>}
      </Form.Group>

      <Button type="submit">{isEdit ? "Update" : "Create"}</Button>
    </Form>
  );
}
