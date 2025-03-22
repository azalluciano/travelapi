import axios from "axios";

const API_URL = "http://127.0.0.1:8000/api";

const api = axios.create({
  baseURL: API_URL,
});

// Add a request interceptor to add the auth token to requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    if (config.data instanceof FormData) {
      delete config.headers["Content-Type"];
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Ajout d'un intercepteur de réponse pour gérer l'expiration du token
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    // Vérifier si l'erreur est due à un token expiré (401 Unauthorized) ou accès interdit (403)
    if (
      error.response &&
      (error.response.status === 401 || error.response.status === 403)
    ) {
      // Token expiré ou invalide, déconnecter l'utilisateur
      localStorage.removeItem("token");

      // Rediriger vers la page d'accueil
      window.location.href = "/";

      // Optionnel : Afficher un message à l'utilisateur
      // alert('Votre session a expiré. Veuillez vous reconnecter.');
    }

    return Promise.reject(error);
  }
);

export default api;
