import http from "./http";

export const AttemptAPI = {
  start: (examId: string) => http.post(`/student/exams/${examId}/start`),
  submit: (id: string) => http.post(`/student/attempts/${id}/submit`)
};
