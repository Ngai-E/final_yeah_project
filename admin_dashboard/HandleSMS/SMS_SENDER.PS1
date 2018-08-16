### DISCLAIMER ###
## This script is a quick-hack to demonstrate
## the basics of sending an SMS using an AT-
## compatible GSM modem connected a local
## serial port through PowerShell.
## No error-handling is implemented and this
## is NOT a script fit for production.
##################
# Create your instance of the SerialPort Class
$serialPort = new-Object System.IO.Ports.SerialPort
# Set various COM-port settings
$serialPort.PortName = "COM15"
$serialPort.BaudRate = 9600
$serialPort.WriteTimeout = 500
$serialPort.ReadTimeout = 3000
$serialPort.DtrEnable = "true"
$serialPort.Close()
# Open the connection
$serialPort.Open()
# Add variables for phone number and the message.
$phoneNumber = "650931636"
$textMessage = "HI CALEP!"
try {
 $serialPort.Open()
}
catch {
 # Wait for 5s and try again
 # Told you this is a quick-hack, right?
 Start-Sleep -Seconds 5
 $serialPort.Open()
}
If ($serialPort.IsOpen -eq $true) {
 # Tell the modem you want to use AT-mode
 $serialPort.Write("AT+CMGF=1`r`n")
 # Start feeding message data to the modem
 # Begin with the phone number, international
 # style and a <CL>... that's the `r`n part
 $serialPort.Write("AT+CMGS=`"$phoneNumber`"`r`n")
 # Give the modem some time to react...
 Start-Sleep -Seconds 1
 # Now, write the message to the modem
 $serialPort.Write("$textMessage`r`n")
 # Send a Ctrl+Z to end the message.
 $serialPort.Write($([char] 26))
 # Wait for modem to send it
 Start-Sleep -Seconds 1
}
# Close the Serial Port connection
$serialPort.Close()
if ($serialPort.IsOpen -eq $false) {
 echo "Port Closed!"
}
# That's all folks
# Now, add call-backs, event-handlers, and return-
# message handling.