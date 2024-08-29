import React, { useState } from "react";
import { Form, Button, Modal, Alert } from "react-bootstrap";
import { useQuery, useMutation } from "@tanstack/react-query";
import axios from "axios";
import { apiEndpoints } from "../utils/api";

const fetchCars = () => axios.get(apiEndpoints.GET_CARS).then((res) => res.data);
const createDriver = (newDriver) => axios.post(apiEndpoints.CREATE_DRIVER, newDriver);

function CreateDriver() {
  const [name, setName] = useState("");
  const [birthDate, setBirthDate] = useState("");
  const [selectedCar, setSelectedCar] = useState("");
  const [showModal, setShowModal] = useState(false);
  const [error, setError] = useState(null);

  const { data: cars, isLoading: carsLoading } = useQuery({ queryKey: ["cars"], queryFn: fetchCars });

  const createDriverMutation = useMutation({
    mutationFn: createDriver,
    onSuccess: () => {
      setShowModal(true);
      setName("");
      setBirthDate("");
      setSelectedCar("");
      setError(null);
    },
    onError: (error) => {
      setError(error.response?.data?.status || "An unexpected error occurred.");
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();

    const [year, month, day] = birthDate.split("-");
    const bodyBirthDate = `${day}-${month}-${year}`;

    const newDriver = {
      name,
      birth_date: bodyBirthDate,
      car: selectedCar || null,
    };

    createDriverMutation.mutate(newDriver);
  };

  if (carsLoading) return <div>Loading...</div>;

  return (
    <>
      <Form onSubmit={handleSubmit}>
        <h2>Create Driver</h2>
        {error && (
          <Alert variant="danger">
            <strong>Error:</strong> {error}
          </Alert>
        )}
        <Form.Group controlId="name">
          <Form.Label>Name</Form.Label>
          <Form.Control type="text" placeholder="Enter driver's name" value={name} onChange={(e) => setName(e.target.value)} />
        </Form.Group>

        <Form.Group controlId="birthDate" className="mt-3">
          <Form.Label>Birth Date</Form.Label>
          <Form.Control type="date" value={birthDate} onChange={(e) => setBirthDate(e.target.value)} />
        </Form.Group>

        <Form.Group controlId="carSelect" className="mt-3">
          <Form.Label>Select Car (Optional)</Form.Label>
          <Form.Control as="select" value={selectedCar} onChange={(e) => setSelectedCar(e.target.value)}>
            <option value="">-- Select Car --</option>
            {cars.map((car) => (
              <option key={car.id} value={car.id}>
                {car.car_number} - {car.model.name} ({car.model.brand.name})
              </option>
            ))}
          </Form.Control>
        </Form.Group>

        <Button variant="primary" type="submit" className="mt-4">
          Create
        </Button>
      </Form>

      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Driver Created</Modal.Title>
        </Modal.Header>
        <Modal.Body>The driver has been successfully created.</Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Close
          </Button>
        </Modal.Footer>
      </Modal>
    </>
  );
}

export default CreateDriver;
