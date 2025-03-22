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

export const updateDestination = async (id, formData) => {
  console.log("Contenu du FormData avant envoi:");
  for (const [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }

  try {
    const response = await api.post(`/destinations/${id}`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    console.log("Réponse reçue:", response.data);
    return response;
  } catch (error) {
    console.error("Erreur détaillée:", {
      message: error.message,
      status: error.response?.status,
      statusText: error.response?.statusText,
      data: error.response?.data,
    });
    throw error;
  }
};

export const deleteDestination = async (id) => {
  return api.delete(`/destinations/${id}`);
};
