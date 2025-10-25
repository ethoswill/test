# Embroidery Widget Documentation

## Overview
The Embroidery Widget is a specialized admin interface for managing embroidery customization options for products in your Medusa store.

## Features

### 🎨 Design File Upload
- **Supported Formats**: JPG, PNG, SVG, PDF, AI, EPS
- **File Management**: Upload, preview, and download design files
- **Storage**: Files are converted to base64 and stored in product metadata

### 📏 Size Configuration
- **Width & Height**: Input fields for precise measurements in inches
- **Area Calculation**: Automatic calculation of total embroidery area
- **Decimal Support**: Supports decimal values (e.g., 2.5 inches)

### 🧵 Embroidery Style Options
- **Flat Embroidery**: Standard flat embroidery technique
- **Puff Embroidery**: 3D raised embroidery for textured effects
- **Mix Style**: Combination of flat and puff techniques

### 🎨 Thread Color Management
- **Custom Input**: Free text field for color names, codes, or Pantone references
- **Quick Select**: Predefined color buttons for common thread colors
- **Examples**: Navy Blue, #1E3A8A, Pantone 286C

### 🧢 Embroidery Type Selection
- **Hats**: Specialized for cap embroidery and curved surfaces
- **Flats**: Standard for shirt embroidery and flat surfaces

### 📝 Additional Features
- **Notes Field**: Space for special instructions or requirements
- **Summary Display**: Real-time summary of all embroidery settings
- **Auto-Save**: Data is automatically saved to product metadata

## How to Use

1. **Access the Widget**:
   - Go to `http://localhost:9000/app/login`
   - Login with admin credentials
   - Navigate to Products → Select a Product
   - Scroll down to find the "🎨 Embroidery Customization" widget

2. **Upload Design File**:
   - Click "Upload Design File" button
   - Select your design file (JPG, PNG, SVG, PDF, AI, EPS)
   - File will be stored and can be downloaded later

3. **Configure Size**:
   - Enter width and height in inches
   - Use decimal values for precise measurements
   - Total area will be calculated automatically

4. **Select Style**:
   - Choose between Flat, Puff, or Mix embroidery styles
   - Each option includes a description

5. **Set Thread Color**:
   - Enter custom color name or code
   - Use quick-select buttons for common colors
   - Supports various color notation formats

6. **Choose Type**:
   - Select "Hats" for cap embroidery
   - Select "Flats" for shirt embroidery

7. **Add Notes**:
   - Include any special instructions
   - Add requirements or specifications

8. **Save Data**:
   - Click "Save Embroidery Data" button
   - All data will be stored in product metadata

## Data Storage

All embroidery data is stored in the product's metadata field as JSON:

```json
{
  "metadata": {
    "embroidery_data": "{\"width\":2.5,\"height\":1.0,\"style\":\"flat\",\"threadColor\":\"Navy Blue\",\"type\":\"flats\",\"fileName\":\"design.svg\",\"file\":\"base64data...\"}"
  }
}
```

## Integration with CSV Export

The embroidery data will be included in product CSV exports, allowing you to:
- Track embroidery specifications across products
- Generate production reports
- Share embroidery requirements with manufacturers

## Customization

The widget can be customized by modifying:
- File upload restrictions
- Embroidery style options
- Thread color presets
- Embroidery type categories
- UI styling and layout

## Technical Details

- **Framework**: React with TypeScript
- **Storage**: Medusa metadata system
- **File Handling**: Base64 encoding for file storage
- **Validation**: Client-side input validation
- **Responsive**: Mobile-friendly design






