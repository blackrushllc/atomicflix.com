# organize.ps1
# This script scans the current folder and organizes files into subdirectories based on their extension.

# Get all files in the current folder, excluding directories and this script itself
$files = Get-ChildItem -File | Where-Object { $_.FullName -ne $PSCommandPath }

foreach ($file in $files) {
    # Get the extension without the leading dot and convert to lowercase
    $extension = $file.Extension.TrimStart('.').ToLower()
    
    # If the file has no extension, use "no_extension" or skip? 
    # Defaulting to "no_extension" to ensure all files are organized.
    if (-not $extension) {
        $extension = "no_extension"
    }

    # Create the target directory if it doesn't exist
    if (-not (Test-Path -Path $extension)) {
        New-Item -ItemType Directory -Path $extension | Out-Null
    }

    # Define the target destination path
    $destinationPath = Join-Path -Path $extension -ChildPath $file.Name

    # Move the file to the target directory, overwriting if it exists
    Move-Item -Path $file.FullName -Destination $destinationPath -Force
}

Write-Host "Files have been organized by extension."
