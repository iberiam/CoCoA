cd Code

cd lib/fastore

echo "Compiling fastore"
make clean
make libore.so

mv libore.so ../
echo "Done compiling fastore"

cd ../..

echo "Creating virtual environment"
python3 -m venv venv

source venv/bin/activate

echo "Installing requirements"
pip install -r requirements.txt

echo ""
echo "------------------------------------"
echo "Done setting up"

echo "Please always run the following command before running the code"
echo "$ source venv/bin/activate"

echo "And run the code inside the Code directory"