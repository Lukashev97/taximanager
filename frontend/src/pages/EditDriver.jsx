import React, { useState, useEffect } from "react";
import { Form, Button, Alert } from "react-bootstrap";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";
import { apiEndpoints } from "../utils/api";

const fetchDriver = (id) => () => axios.get(`${apiEndpoints.GET_DRIVERS}/${id}`).then((res) => res.data);
const fetchCars = () => axios.get(apiEndpoints.GET_CARS).then((res) => res.data);

function EditDriver() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [formData, setFormData] = useState({ name: "", birth_date: null, car: null });
  const [error, setError] = useState(null);

  const { data: driver, isLoading: driverLoading } = useQuery({ queryKey: ["driver", id], queryFn: fetchDriver(id) });
  const { data: cars, isLoading: carsLoading } = useQuery({ queryKey: ["cars"], queryFn: fetchCars });

  const mutation = useMutation({
    mutationFn: (updatedData) => axios.put(`${apiEndpoints.GET_DRIVERS}/${id}`, updatedData),
    onSuccess: () => {
      navigate("/drivers");
    },
    onError: (error) => {
      setError(error.response.data);
    },
  });

  useEffect(() => {
    if (driver) {
      setFormData({
        name: driver.name,
        birth_date: new Date(driver.birth_date).toISOString().split("T")[0],
        car: driver.car ? driver.car.id : null,
      });
    }
  }, [driver]);

  const handleChange = (event) => {
    setFormData({
      ...formData,
      [event.target.name]: event.target.value,
    });
  };

  const handleSubmit = (event) => {
    event.preventDefault();

    const updatedData = {};

    if (formData.name) {
      updatedData.name = formData.name;
    }
    if (formData.birth_date) {
      const [year, month, day] = formData.birth_date.split("-");
      const bodyBirthDate = `${day}-${month}-${year}`;
      updatedData.birth_date = bodyBirthDate;
    }
    if (formData.car) {
      updatedData.car = formData.car;
    }

    console.log("updatedDAte", updatedData);

    mutation.mutate(updatedData);
  };

  if (driverLoading || carsLoading) {
    return <div>Loading...</div>;
  }

  return (
    <div>
      <h2>Edit Driver</h2>
      {error && (
        <Alert variant="danger">
          <strong>Error:</strong> {error.status}
        </Alert>
      )}
      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="formName">
          <Form.Label>Name</Form.Label>
          <Form.Control type="text" name="name" value={formData.name || ""} onChange={handleChange} />
        </Form.Group>

        <Form.Group controlId="birth_date" className="mt-3">
          <Form.Label>Birth Date</Form.Label>
          <Form.Control name="birth_date" type="date" value={formData.birth_date || ""} onChange={handleChange} />
        </Form.Group>

        <Form.Group controlId="formCar" className="mt-2">
          <Form.Label>Car</Form.Label>
          <Form.Control as="select" name="car" value={formData.car || ""} onChange={handleChange}>
            <option value="">Select a car</option>
            {cars.map((car) => (
              <option key={car.id} value={car.id}>
                {car.car_number} - {car.model.name} ({car.model.brand.name})
              </option>
            ))}
          </Form.Control>
        </Form.Group>

        <Button variant="primary" type="submit" className="mt-3">
          Update Driver
        </Button>
      </Form>
    </div>
  );
}

export default EditDriver;
