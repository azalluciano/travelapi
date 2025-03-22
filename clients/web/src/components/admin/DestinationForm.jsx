import React, { useState, useRef } from "react";

/**
 * DestinationForm Component
 * A responsive form for creating or updating travel destinations with image upload capability.
 *
 * @param {Object} initialData - Pre-filled data for editing mode
 * @param {Function} onSubmit - Handler for form submission
 * @param {String} buttonText - Custom text for the submit button
 */
const DestinationForm = ({
  initialData = {},
  onSubmit,
  buttonText = "Save",
}) => {
  // Form state management
  const [formData, setFormData] = useState({
    name: initialData.name || "",
    description: initialData.description || "",
    price: initialData.price || "",
    duration: initialData.duration || "",
  });

  // Image handling states
  const [imageFile, setImageFile] = useState(null);
  const [imagePreview, setImagePreview] = useState(initialData.image || "");
  const [errors, setErrors] = useState({});
  const fileInputRef = useRef(null);

  /**
   * Updates form data when input fields change
   */
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

  /**
   * Handles image file selection and preview
   */
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

  /**
   * Validates form inputs before submission
   * @returns {Boolean} - Whether the form is valid
   */
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

  /**
   * Handles form submission
   */
  const handleSubmit = async (e) => {
    e.preventDefault();

    if (validate()) {
      // Create a FormData object to send the file and other form data
      const formDataToSubmit = new FormData();

      // Add text fields
      formDataToSubmit.append("name", formData.name);
      formDataToSubmit.append("description", formData.description);
      formDataToSubmit.append("price", formData.price.toString());
      formDataToSubmit.append("duration", formData.duration.toString());

      // Add image file if a new one was selected
      if (imageFile) {
        formDataToSubmit.append("image", imageFile);
      }

      // Call the onSubmit function with the FormData object
      onSubmit(formDataToSubmit);
    }
  };

  // Input field style classes
  const inputClass = (fieldName) => `
    w-full p-3 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400
    ${errors[fieldName] ? "border-red-500" : "border-gray-300"}
  `;

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-6 max-w-2xl mx-auto p-4 md:p-6 bg-white rounded-lg shadow-md"
      encType="multipart/form-data"
    >
      <h2 className="text-2xl font-bold text-gray-800 mb-6">
        {initialData.name ? "Edit Destination" : "Add New Destination"}
      </h2>

      <div>
        <label htmlFor="name" className="block text-gray-700 font-medium mb-2">
          Destination Name
        </label>
        <input
          type="text"
          id="name"
          name="name"
          value={formData.name}
          onChange={handleChange}
          placeholder="Enter destination name"
          className={inputClass("name")}
        />
        {errors.name && (
          <p className="text-red-500 text-sm mt-1">{errors.name}</p>
        )}
      </div>

      <div>
        <label
          htmlFor="description"
          className="block text-gray-700 font-medium mb-2"
        >
          Description
        </label>
        <textarea
          id="description"
          name="description"
          value={formData.description}
          onChange={handleChange}
          placeholder="Describe this destination"
          rows="4"
          className={inputClass("description")}
        ></textarea>
        {errors.description && (
          <p className="text-red-500 text-sm mt-1">{errors.description}</p>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label
            htmlFor="price"
            className="block text-gray-700 font-medium mb-2"
          >
            Price ($)
          </label>
          <input
            type="number"
            id="price"
            name="price"
            value={formData.price}
            onChange={handleChange}
            step="0.01"
            min="0"
            placeholder="0.00"
            className={inputClass("price")}
          />
          {errors.price && (
            <p className="text-red-500 text-sm mt-1">{errors.price}</p>
          )}
        </div>

        <div>
          <label
            htmlFor="duration"
            className="block text-gray-700 font-medium mb-2"
          >
            Duration (days)
          </label>
          <input
            type="number"
            id="duration"
            name="duration"
            value={formData.duration}
            onChange={handleChange}
            min="1"
            placeholder="0"
            className={inputClass("duration")}
          />
          {errors.duration && (
            <p className="text-red-500 text-sm mt-1">{errors.duration}</p>
          )}
        </div>
      </div>

      <div>
        <label htmlFor="image" className="block text-gray-700 font-medium mb-2">
          Destination Image
        </label>
        <div className="flex flex-col md:flex-row md:items-center gap-4">
          <div className="w-full">
            <div className="relative">
              <input
                type="file"
                id="image"
                name="image"
                ref={fileInputRef}
                onChange={handleImageChange}
                accept="image/*"
                className={`w-full p-3 border rounded-lg ${
                  errors.image ? "border-red-500" : "border-gray-300"
                } file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100`}
              />
            </div>
            {errors.image && (
              <p className="text-red-500 text-sm mt-1">{errors.image}</p>
            )}
          </div>

          {/* Image preview */}
          {imagePreview && (
            <div className="mt-2 md:mt-0 w-full md:w-1/3">
              <div className="aspect-video relative rounded-lg overflow-hidden border border-gray-300">
                <img
                  src={imagePreview}
                  alt="Preview"
                  className="w-full h-full object-cover"
                />
              </div>
            </div>
          )}
        </div>
      </div>

      <button
        type="submit"
        className="w-full md:w-auto bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm"
      >
        {buttonText}
      </button>
    </form>
  );
};

export default DestinationForm;
