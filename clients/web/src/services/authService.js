import api from "./api";

export const login = async (credentials) => {
  const response = await api.post("/auth/login", credentials);
  if (response.data.token) {
    localStorage.setItem("token", response.data.token);
  }
  return response;
};

export const register = async (userData) => {
  return api.post("/auth/register", userData);
};

export const logout = async () => {
  try {
    await api.post("/auth/logout");
  } finally {
    localStorage.removeItem("token");
  }
};

export const getCurrentUser = async () => {
  return api.get("/auth/me");
};

export const isAuthenticated = () => {
  return !!localStorage.getItem("token");
};
