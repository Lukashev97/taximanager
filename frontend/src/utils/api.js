const API_URL = import.meta.env.VITE_API_URL

const apiEndpoints = {
  GET_BRANDS: `${API_URL}/api/brands`,
  GET_MODELS: `${API_URL}/api/models`,
  CREATE_CAR: `${API_URL}/api/cars`,
  GET_DRIVERS: `${API_URL}/api/drivers`,
  GET_CARS: `${API_URL}/api/cars`,
  CREATE_DRIVER: `${API_URL}/api/drivers`,
  GET_LOGS: `${API_URL}/api/logs`
}

export { apiEndpoints }

