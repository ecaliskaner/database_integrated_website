using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Trex_Proje1
{
    public partial class Form2 : Form
    {
        public Form2()
        {
            InitializeComponent();
        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }



        private void AnimalType_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void Gender_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void Add_Click(object sender, EventArgs e)
        {
            // Assume you have ComboBoxes: AnimalType (for type), Gender (for gender), and a TextBox: Age (for age)
            string selectedType = AnimalType.SelectedItem?.ToString();
            string selectedGender = Gender.SelectedItem?.ToString();
            int selectedAge = 0;
            int.TryParse(Age.Text, out selectedAge);

            Form1.Animal animal = null;
            switch (selectedType)
            {
                case "Cow":
                    animal = new Form1.Cow();
                    break;
                case "Sheep":
                    animal = new Form1.Sheep();
                    break;
                case "Chicken":
                    animal = new Form1.Chicken();
                    break;
                case "Goat":
                    animal = new Form1.Goat();
                    break;
            }
            if (animal != null)
            {
                animal.Age = selectedAge;
                animal.Gender = selectedGender;
                if (Form1.TryBuyAnimal(animal))
                {
                    MessageBox.Show($"{selectedType} added to the barn.");
                    this.Close(); // Optionally close Form2 after adding
                }
                else
                {
                    MessageBox.Show($"Not enough money to buy a {selectedType}!");
                }
            }
            else
            {
                MessageBox.Show("Please select a valid animal type.");
            }

        }

        private void Count_TextChanged(object sender, EventArgs e)
        {

        }

        private void textBox1_TextChanged_1(object sender, EventArgs e)
        {

        }

        private void Age_TextChanged(object sender, EventArgs e)
        {

        }
    }
}
