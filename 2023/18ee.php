<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colorful Picture</title>
</head>
<body>
<canvas id="myCanvas" width="400" height="400" style="border:1px solid #000;"></canvas>

<script>
    // Function to draw a colorful picture on a canvas with a frame
    function drawColorfulPicture(instructions) {
        const canvas = document.getElementById('myCanvas');
        const ctx = canvas.getContext('2d');

        let currentX = canvas.width / 2; // starting position X
        let currentY = canvas.height / 2; // starting position Y

        // Set up the frame
        ctx.beginPath();
        ctx.rect(0, 0, canvas.width, canvas.height);
        ctx.stroke();

        // Loop through each instruction
        instructions.forEach(instruction => {
            const direction = instruction[0];
            const distance = instruction[1];
            const color = instruction[2];

            // Update position based on the instruction
            switch (direction) {
                case 'R':
                    currentX += distance;
                    break;
                case 'D':
                    currentY += distance;
                    break;
                case 'L':
                    currentX -= distance;
                    break;
                case 'U':
                    currentY -= distance;
                    break;
                // Add more cases for other directions if needed
            }

            // Draw a line to the new position with the specified color
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.moveTo(currentX - distance, currentY - distance);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();
        });
    }

    // Read and parse instructions from the '18.txt' file
    const fileContent = `<?php echo file_get_contents('18.txt'); ?>`;
    const lines = fileContent.trim().split('\n');

    const instructions = lines.map(line => {
        const parts = line.split(' ');
        const direction = parts[0];
        const distance = parseInt(parts[1]);
        const color = parts[2] || '#000000';
        return [direction, distance, color];
    });

    // Call the function with the instructions from the file
    drawColorfulPicture(instructions);
</script>
</body>
</html>
