import api from "./api";

export const getDestinations = async (filters = {}) => {
  const params = new URLSearchParams();

  // Add filters if they exist
  if (filters.name) {
    params.append("name", filters.name);
  }

  return api.get(`/destinations?${params.toString()}`);
};

export const getDestination = async (id) => {
  return api.get(`/destinations/${id}`);
};

export const createDestination = async (destinationData) => {
  return api.post("/destinations", destinationData);
};

export const updateDestination = async (id, destinationData) => {
  return api.put(`/destinations/${id}`, destinationData);
};

export const deleteDestination = async (id) => {
  return api.delete(`/destinations/${id}`);
};
