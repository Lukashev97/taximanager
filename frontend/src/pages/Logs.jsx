import React from "react";
import { Table, Alert } from "react-bootstrap";
import { useQuery } from "@tanstack/react-query";
import axios from "axios";
import { apiEndpoints } from "../utils/api";

const fetchLogs = () => axios.get(apiEndpoints.GET_LOGS).then((res) => res.data);

function Logs() {
  const { data: logs, isLoading, isError, error } = useQuery({ queryKey: ["logs"], queryFn: fetchLogs });

  if (isLoading) {
    return <div>Loading logs...</div>;
  }

  if (isError) {
    return (
      <Alert variant="danger">
        <strong>Error:</strong> {error.message}
      </Alert>
    );
  }

  return (
    <div>
      <h2>Logs</h2>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>#</th>
            <th>Event Date</th>
            <th>Driver</th>
            <th>Car</th>
            <th>Text</th>
          </tr>
        </thead>
        <tbody>
          {logs.map((log) => (
            <tr key={log.id}>
              <td>{log.id}</td>
              <td>{new Date(log.event_date).toLocaleString()}</td>
              <td>{log.driver}</td>
              <td>{log.car}</td>
              <td>{log.text}</td>
            </tr>
          ))}
        </tbody>
      </Table>
    </div>
  );
}

export default Logs;
