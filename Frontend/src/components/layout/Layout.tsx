import ViewSwitcher from "./ViewSwitcher";

export default function Layout({ children }: any) {
  return (
    <div className="container py-4">
      <h2>Exam Management System</h2>
      <ViewSwitcher />
      {children}
    </div>
  );
}
