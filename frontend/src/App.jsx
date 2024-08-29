import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Container from "react-bootstrap/Container";
import Navbar from "react-bootstrap/Navbar";
import Nav from "react-bootstrap/Nav";
import CreateCar from "./pages/CreateCar";
import CreateDriver from "./pages/CreateDriver";
import DriverList from "./pages/DriverList";
import EditDriver from "./pages/EditDriver";
import Logs from "./pages/Logs";
import "./App.css";

const queryClient = new QueryClient();

function App() {
  return (
    <>
      <QueryClientProvider client={queryClient}>
        <Router>
          <Navbar bg="dark" variant="dark" expand="lg">
            <Container>
              <Navbar.Brand href="/">Car Management</Navbar.Brand>
              <Nav className="me-auto">
                <Nav.Link href="/create-car">CreateCar</Nav.Link>
                <Nav.Link href="/create-driver">Create Driver</Nav.Link>
                <Nav.Link href="/drivers">Driver List</Nav.Link>
                <Nav.Link href="/logs">Logs</Nav.Link>
              </Nav>
            </Container>
          </Navbar>
          <Container className="mt-4">
            <Routes>
              <Route path="/" element={<h1>Welcome to Car Management</h1>} />
              <Route path="/create-car" element={<CreateCar />} />
              <Route path="/create-driver" element={<CreateDriver />} />
              <Route path="/drivers" element={<DriverList />} />
              <Route path="/edit-driver/:id" element={<EditDriver />} />
              <Route path="/logs" element={<Logs />} />
            </Routes>
          </Container>
        </Router>
      </QueryClientProvider>
    </>
  );
}

export default App;
