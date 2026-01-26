import http from "./http";

export const ExamAPI = {
  create: (data: any) => http.post("/admin/exams", data),
  update: (id: string, data: any) => http.put(`/admin/exams/${id}`, data),
  attempts: (id: string) => http.get(`/admin/exams/${id}/attempts`),

  dashboard: () => http.get("/student/exams"),
  myAttempts: (id: string) => http.get(`/student/exams/${id}/attempts`)
};
