import React, { useState, useRef } from "react";

// Component for creating or updating destinations with image upload
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
  });

  // Store the actual image file separately
  const [imageFile, setImageFile] = useState(null);
  const [imagePreview, setImagePreview] = useState(initialData.image || "");
  const [errors, setErrors] = useState({});
  const fileInputRef = useRef(null);

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

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImageFile(file);

      // Create a preview URL for the selected image
      const previewUrl = URL.createObjectURL(file);
      setImagePreview(previewUrl);

      // Clear error if there was one
      if (errors.image) {
        setErrors({
          ...errors,
          image: null,
        });
      }
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

    // Check if we have an image file or if an image URL already exists for edit mode
    if (!imageFile && !initialData.image) {
      newErrors.image = "Image is required";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (validate()) {
      // Create a FormData object to send the file and other form data
      const formDataToSubmit = new FormData();

      // Add text fields
      formDataToSubmit.append("name", formData.name);
      formDataToSubmit.append("description", formData.description);
      formDataToSubmit.append("price", Number(formData.price));
      formDataToSubmit.append("duration", Number(formData.duration));

      // Add image file if a new one was selected
      if (imageFile) {
        formDataToSubmit.append("image", imageFile);
      } else if (initialData.image) {
        // Si nous éditons et qu'aucune nouvelle image n'a été sélectionnée,
        // nous n'envoyons pas l'image existante car elle est déjà stockée
        // Laissez Laravel ignorer ce champ et conserver l'image existante
      }

      // Call the onSubmit function with the FormData object
      onSubmit(formDataToSubmit);
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-4"
      encType="multipart/form-data"
    >
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
          Image
        </label>
        <input
          type="file"
          id="image"
          name="image"
          ref={fileInputRef}
          onChange={handleImageChange}
          accept="image/*"
          className={`w-full p-2 border rounded ${
            errors.image ? "border-red-500" : "border-gray-300"
          }`}
        />
        {errors.image && (
          <p className="text-red-500 text-sm mt-1">{errors.image}</p>
        )}

        {/* Image preview */}
        {imagePreview && (
          <div className="mt-2">
            <img
              src={imagePreview}
              alt="Preview"
              className="h-32 object-cover rounded border border-gray-300"
            />
          </div>
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
