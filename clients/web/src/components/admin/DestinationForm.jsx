import React, { useState } from "react";

// Component for creating or updating destinations
const DestinationForm = ({
  initialData = {},
  onSubmit,
  buttonText = "Save",
}) => {
  const [formData, setFormData] = useState({
    name: initialData.name || "",
    description: initialData.description || "",
    price: initialData.price || "",
    duration: initialData.duration || "",
    image: initialData.image || "",
  });
  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });

    // Clear error when field is edited
    if (errors[e.target.name]) {
      setErrors({
        ...errors,
        [e.target.name]: null,
      });
    }
  };

  const validate = () => {
    const newErrors = {};

    if (!formData.name.trim()) {
      newErrors.name = "Name is required";
    }

    if (!formData.description.trim()) {
      newErrors.description = "Description is required";
    }

    if (!formData.price) {
      newErrors.price = "Price is required";
    } else if (isNaN(formData.price) || Number(formData.price) <= 0) {
      newErrors.price = "Price must be a positive number";
    }

    if (!formData.duration) {
      newErrors.duration = "Duration is required";
    } else if (isNaN(formData.duration) || Number(formData.duration) <= 0) {
      newErrors.duration = "Duration must be a positive number";
    }

    if (!formData.image.trim()) {
      newErrors.image = "Image URL is required";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    if (validate()) {
      // Convert numeric fields to numbers
      const formattedData = {
        ...formData,
        price: Number(formData.price),
        duration: Number(formData.duration),
      };

      onSubmit(formattedData);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label htmlFor="name" className="block text-gray-700 mb-1">
          Name
        </label>
        <input
          type="text"
          id="name"
          name="name"
          value={formData.name}
          onChange={handleChange}
          className={`w-full p-2 border rounded ${
            errors.name ? "border-red-500" : "border-gray-300"
          }`}
        />
        {errors.name && (
          <p className="text-red-500 text-sm mt-1">{errors.name}</p>
        )}
      </div>

      <div>
        <label htmlFor="description" className="block text-gray-700 mb-1">
          Description
        </label>
        <textarea
          id="description"
          name="description"
          value={formData.description}
          onChange={handleChange}
          rows="4"
          className={`w-full p-2 border rounded ${
            errors.description ? "border-red-500" : "border-gray-300"
          }`}
        ></textarea>
        {errors.description && (
          <p className="text-red-500 text-sm mt-1">{errors.description}</p>
        )}
      </div>

      <div>
        <label htmlFor="price" className="block text-gray-700 mb-1">
          Price ($)
        </label>
        <input
          type="number"
          id="price"
          name="price"
          value={formData.price}
          onChange={handleChange}
          step="0.01"
          className={`w-full p-2 border rounded ${
            errors.price ? "border-red-500" : "border-gray-300"
          }`}
        />
        {errors.price && (
          <p className="text-red-500 text-sm mt-1">{errors.price}</p>
        )}
      </div>

      <div>
        <label htmlFor="duration" className="block text-gray-700 mb-1">
          Duration (days)
        </label>
        <input
          type="number"
          id="duration"
          name="duration"
          value={formData.duration}
          onChange={handleChange}
          className={`w-full p-2 border rounded ${
            errors.duration ? "border-red-500" : "border-gray-300"
          }`}
        />
        {errors.duration && (
          <p className="text-red-500 text-sm mt-1">{errors.duration}</p>
        )}
      </div>

      <div>
        <label htmlFor="image" className="block text-gray-700 mb-1">
          Image URL
        </label>
        <input
          type="text"
          id="image"
          name="image"
          value={formData.image}
          onChange={handleChange}
          className={`w-full p-2 border rounded ${
            errors.image ? "border-red-500" : "border-gray-300"
          }`}
        />
        {errors.image && (
          <p className="text-red-500 text-sm mt-1">{errors.image}</p>
        )}
      </div>

      <button
        type="submit"
        className="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition"
      >
        {buttonText}
      </button>
    </form>
  );
};

export default DestinationForm;
