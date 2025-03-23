
# React Destinations App

A simple React application for viewing and filtering travel destinations, with the ability to export data to CSV.

## Features

- Display a list of destinations on the homepage
- View detailed information of each destination
- Filter destinations by name
- Export destinations to CSV with a button
- Fetch data from an API endpoint

## Requirements

- Node.js >= 14.x
- React >= 18.x
- Axios (for API requests)
- React CSV (for CSV export functionality)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/azalluciano/travelapi.git
   cd travelapi/clients/web
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm start
   ```

4. Open your browser and visit `http://localhost:3000`.

## API Integration

The app interacts with an external API to fetch and display the destinations. It uses Axios to make HTTP requests.

### Fetch Destinations

The `getDestinations` function fetches a list of destinations with optional filtering by name.

```javascript
export const getDestinations = async (filters = {}) => {
  const params = new URLSearchParams();

  // Add filters if they exist
  if (filters.name) {
    params.append("name", filters.name);
  }

  return api.get(`/destinations?${params.toString()}`);
};
```

### Fetch Destination Details

The `getDestination` function fetches detailed information of a single destination.

```javascript
export const getDestination = async (id) => {
  return api.get(`/destinations/${id}`);
};
```

## Components

### HomePage

The homepage displays a list of destinations, allows the user to filter by name, and provides a button to export the destinations to a CSV file.

### Destination Details

Each destination has a detailed view with more information, accessible by clicking on a destination name.

### Filter

Users can filter the destinations by entering a name in the filter input. The destinations are updated dynamically as the user types.

### Export Button

A button is provided to export the filtered destinations to a CSV file.



## API Endpoints

### List Destinations
- **URL**: `/api/destinations`
- **Method**: GET
- **Parameters**:
  - `name` (optional) - Filter destinations by name
- **Example**: `GET /api/destinations?name=Paris`

### Get Destination Details
- **URL**: `/api/destinations/{id}`
- **Method**: GET
- **Example**: `GET /api/destinations/1`





