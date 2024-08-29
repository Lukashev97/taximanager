import React from "react";
import { Table, Button } from "react-bootstrap";
import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import axios from "axios";
import { apiEndpoints } from "../utils/api";

const fetchDrivers = () => axios.get(apiEndpoints.GET_DRIVERS).then((res) => res.data);

function DriverList() {
  const { data: drivers, isLoading, error } = useQuery({ queryKey: ["drivers"], queryFn: fetchDrivers });

  if (isLoading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  return (
    <div>
      <h2>Driver List</h2>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Birth Date</th>
            <th>Car Number</th>
            <th>Car Model</th>
            <th>Car Brand</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {drivers.map((driver) => (
            <tr key={driver.id}>
              <td>{driver.id}</td>
              <td>{driver.name}</td>
              <td>{new Date(driver.birth_date).toLocaleDateString()}</td>
              <td>{driver.car?.car_number}</td>
              <td>{driver.car?.model.name}</td>
              <td>{driver.car?.model.brand.name}</td>
              <td>
                <Link to={`/edit-driver/${driver.id}`}>
                  <Button variant="warning" size="sm">
                    Edit
                  </Button>
                </Link>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
    </div>
  );
}

export default DriverList;
