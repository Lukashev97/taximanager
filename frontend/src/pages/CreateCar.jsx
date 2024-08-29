import React, { useState } from "react";
import { Form, Button, Modal, Table } from "react-bootstrap";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "axios";
import { apiEndpoints } from "../utils/api";

const fetchBrands = () => axios.get(apiEndpoints.GET_BRANDS).then((res) => res.data);
const fetchModels = () => axios.get(apiEndpoints.GET_MODELS).then((res) => res.data);
const fetchCars = () => axios.get(apiEndpoints.GET_CARS).then((res) => res.data);
const createCar = (newCar) => axios.post(apiEndpoints.CREATE_CAR, newCar);

function CreateCar() {
  const [selectedBrand, setSelectedBrand] = useState("");
  const [selectedModel, setSelectedModel] = useState("");
  const [carNumber, setCarNumber] = useState("");
  const [showModal, setShowModal] = useState(false);

  const queryClient = useQueryClient();

  const { data: brands, isLoading: brandsLoading } = useQuery({ queryKey: ["brands"], queryFn: fetchBrands });
  const { data: models, isLoading: modelsLoading } = useQuery({ queryKey: ["models"], queryFn: fetchModels });
  const { data: cars, isLoading: carsLoading } = useQuery({ queryKey: ["cars"], queryFn: fetchCars });

  const createCarMutation = useMutation({
    mutationFn: createCar,
    onSuccess: () => {
      queryClient.invalidateQueries(["cars"]); // Invalidate cache to refresh car list
      setShowModal(true);
      setSelectedBrand("");
      setSelectedModel("");
      setCarNumber("");
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    if (selectedModel && carNumber) {
      createCarMutation.mutate({
        car_number: carNumber,
        model: selectedModel,
      });
    }
  };

  if (brandsLoading || modelsLoading || carsLoading) return <div>Loading...</div>;

  const filteredModels = models.filter((model) => model.brand.id === parseInt(selectedBrand));

  return (
    <>
      <Form onSubmit={handleSubmit}>
        <h2>Create Car</h2>
        <Form.Group controlId="brandSelect">
          <Form.Label>Select Brand</Form.Label>
          <Form.Control as="select" value={selectedBrand} onChange={(e) => setSelectedBrand(e.target.value)}>
            <option value="">-- Select Brand --</option>
            {brands.map((brand) => (
              <option key={brand.id} value={brand.id}>
                {brand.name}
              </option>
            ))}
          </Form.Control>
        </Form.Group>

        <Form.Group controlId="modelSelect" className="mt-3">
          <Form.Label>Select Model</Form.Label>
          <Form.Control as="select" value={selectedModel} onChange={(e) => setSelectedModel(e.target.value)} disabled={!selectedBrand}>
            <option value="">-- Select Model --</option>
            {filteredModels.map((model) => (
              <option key={model.id} value={model.id}>
                {model.name}
              </option>
            ))}
          </Form.Control>
        </Form.Group>

        <Form.Group controlId="carNumber" className="mt-3">
          <Form.Label>Car Number</Form.Label>
          <Form.Control type="text" placeholder="Enter car number" value={carNumber} onChange={(e) => setCarNumber(e.target.value)} />
        </Form.Group>

        <Button variant="primary" type="submit" className="mt-4">
          Create
        </Button>
      </Form>

      <Table striped bordered hover className="mt-5">
        <thead>
          <tr>
            <th>#</th>
            <th>Car Number</th>
            <th>Model</th>
            <th>Brand</th>
          </tr>
        </thead>
        <tbody>
          {cars.map((car) => (
            <tr key={car.id}>
              <td>{car.id}</td>
              <td>{car.car_number}</td>
              <td>{car.model.name}</td>
              <td>{car.model.brand.name}</td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Car Created</Modal.Title>
        </Modal.Header>
        <Modal.Body>The car has been successfully created.</Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Close
          </Button>
        </Modal.Footer>
      </Modal>
    </>
  );
}

export default CreateCar;
