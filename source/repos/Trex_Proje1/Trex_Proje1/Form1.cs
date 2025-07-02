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

    public partial class Form1 : Form
    {

        public class Animal
        {
            public virtual string ProductName { get; set; }
            public int Age { get; set; }
            public string Gender { get; set; }
            public int Lifetime { get; set; } 
            public int TimeAlive { get; set; }
            public int ProductInterval { get; set; } 
            public int TimeSinceLastProduct { get; set; } 
            public int ProductCount { get; set; } 
            public bool IsAlive { get; set; } = true;

            public Animal()
            {
                TimeAlive = 0;
                TimeSinceLastProduct = 0;
                ProductCount = 0;
                IsAlive = true;
            }

            public virtual int ProduceProduct()
            {
                if (!IsAlive) return 0;
                if (TimeSinceLastProduct >= ProductInterval)
                {
                    ProductCount++;
                    TimeSinceLastProduct = 0;
                    return 1;
                }
                return 0;
            }

            public void Tick(int seconds = 1)
            {
                if (!IsAlive) return;
                TimeAlive += seconds;
                TimeSinceLastProduct += seconds;
                if (TimeAlive >= Lifetime)
                {
                    IsAlive = false;
                }
            }
        }

        public class Cow : Animal
        {
            public Cow()
            {
                ProductName = "Milk";
                ProductInterval = 8; // example: every 8 seconds
                Lifetime = 60; // example: 1 minute
            }
            public override int ProduceProduct()
            {
                return base.ProduceProduct();
            }
        }

        public class Sheep : Animal
        {
            public Sheep()
            {
                ProductName = "Wool";
                ProductInterval = 12;
                Lifetime = 50;
            }
            public override int ProduceProduct()
            {
                return base.ProduceProduct();
            }
        }

        public class Chicken : Animal
        {
            public Chicken()
            {
                ProductName = "Egg";
                ProductInterval = 5;
                Lifetime = 40;
            }
            public override int ProduceProduct()
            {
                return base.ProduceProduct();
            }
        }

        public class Goat : Animal
        {
            public Goat()
            {
                ProductName = "Goat Milk";
                ProductInterval = 10;
                Lifetime = 55;
            }
            public override int ProduceProduct()
            {
                return base.ProduceProduct();
            }
        }

        
        public static int Cashbox = 80;
        public static Dictionary<string, int> ProductPrices = new Dictionary<string, int>
        {
            {"Milk", 20},
            {"Wool", 15},
            {"Egg", 8},
            {"Goat Milk", 10}
        };
        public static Dictionary<string, int> AnimalPrices = new Dictionary<string, int>
        {
            {"Chicken", 30},
            {"Goat", 50},
            {"Sheep", 80},
            {"Cow", 120}
        };
        public static List<Animal> Animals = new List<Animal>();

        public static void SellProduct(string productName, int quantity)
        {
            if (ProductPrices.ContainsKey(productName))
            {
                Cashbox += ProductPrices[productName] * quantity;
                UpdateCashboxProgressBar();
            }
        }
        public Form1()
        {
            InitializeComponent();
            MoneyBar.Minimum = 0;
            MoneyBar.Maximum = 1000; // Set a reasonable max value for cashbox
            MoneyBar.Value = 0;
            
            this.Shown += (s, e) =>
            {
                UpdateCashboxProgressBar();
                MessageBox.Show("Welcome to our Barn! You start with 80 dollars.", "Welcome", MessageBoxButtons.OK, MessageBoxIcon.Information);
            };
           
        }

        
        public static void UpdateCashboxProgressBar()
        {
            foreach (Form f in Application.OpenForms)
            {
                if (f is Form1 form1)
                {
                    form1.MoneyBar.Value = Math.Min(Cashbox, form1.MoneyBar.Maximum);
                }
            }
        }

        
        public static bool TryBuyAnimal(Animal animal)
        {
            if (animal != null)
            {
                int price = 0;
                if (AnimalPrices.TryGetValue(animal.GetType().Name, out price) && Cashbox >= price)
                {
                    Animals.Add(animal);
                    Cashbox -= price;
                    UpdateCashboxProgressBar();
                    return true;
                }
            }
            return false;
        }

       


        
        public void UpdateProductBars()
        {
            // MilkBar: sum of all cow products
            if (MilkBar != null)
            {
                int totalMilk = Animals.Where(a => a is Cow).Sum(a => a.ProductCount);
                MilkBar.Minimum = 0;
                MilkBar.Maximum = 100;
                MilkBar.Value = Math.Min(totalMilk, MilkBar.Maximum);
            }
            // WoolBar: sum of all sheep products
            if (WoolBar != null)
            {
                int totalWool = Animals.Where(a => a is Sheep).Sum(a => a.ProductCount);
                WoolBar.Minimum = 0;
                WoolBar.Maximum = 100;
                WoolBar.Value = Math.Min(totalWool, WoolBar.Maximum);
            }
            // EggBar: sum of all chicken products
            if (EggBar != null)
            {
                int totalEgg = Animals.Where(a => a is Chicken).Sum(a => a.ProductCount);
                EggBar.Minimum = 0;
                EggBar.Maximum = 100;
                EggBar.Value = Math.Min(totalEgg, EggBar.Maximum);
            }
            // GoatMilkBar: sum of all goat products
            if (GoatMilkBar != null)
            {
                int totalGoatMilk = Animals.Where(a => a is Goat).Sum(a => a.ProductCount);
                GoatMilkBar.Minimum = 0;
                GoatMilkBar.Maximum = 100;
                GoatMilkBar.Value = Math.Min(totalGoatMilk, GoatMilkBar.Maximum);
            }
        }

        
        public void UpdateAnimalAreas()
        {
            ListBoxCows.Items.Clear();
            foreach (var animal in Animals)
                if (animal is Cow)
                    ListBoxCows.Items.Add($"Age: {animal.Age}, Gender: {animal.Gender}, Products: {animal.ProductCount}");

            ListBoxSheeps.Items.Clear();
            foreach (var animal in Animals)
                if (animal is Sheep)
                    ListBoxSheeps.Items.Add($"Age: {animal.Age}, Gender: {animal.Gender}, Products: {animal.ProductCount}");

            ListBoxChicken.Items.Clear();
            foreach (var animal in Animals)
                if (animal is Chicken)
                    ListBoxChicken.Items.Add($"Age: {animal.Age}, Gender: {animal.Gender}, Products: {animal.ProductCount}");

            ListBoxGoats.Items.Clear();
            foreach (var animal in Animals)
                if (animal is Goat)
                    ListBoxGoats.Items.Add($"Age: {animal.Age}, Gender: {animal.Gender}, Products: {animal.ProductCount}");
            UpdateProductBars();
        }

        private void hScrollBar1_Scroll(object sender, ScrollEventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void label2_Click(object sender, EventArgs e)
        {

        }



        private void groupBox2_Enter(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            Form2 form2 = new Form2();
            form2.Show();

        }

        private void CowArea_Enter(object sender, EventArgs e)
        {

        }

        private void GoatArea_Enter(object sender, EventArgs e)
        {

        }

        private void ChickenArea_Enter(object sender, EventArgs e)
        {

        }

        private void SheepArea_Enter(object sender, EventArgs e)
        {

        }

        private void Sell_Click(object sender, EventArgs e)
        {
            int totalSold = 0;
            int totalMoney = 0;
            foreach (var animal in Animals)
            {
                if (animal.IsAlive && animal.ProductCount > 0)
                {
                    int productCount = animal.ProductCount;
                    int price = 0;
                    if (ProductPrices.TryGetValue(animal.ProductName, out price))
                    {
                        totalMoney += productCount * price;
                    }
                    totalSold += productCount;
                    animal.ProductCount = 0;
                }
            }
            Cashbox += totalMoney;
            UpdateCashboxProgressBar();
            UpdateAnimalAreas();
            MessageBox.Show($"{totalSold} products sold for {totalMoney} money.");
        }

        private void textBox2_TextChanged(object sender, EventArgs e)
        {

        }

        private void Product_Count_TextChanged(object sender, EventArgs e)
        {

        }

        private void istBoxCows_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void ListBoxChicken_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void ListBoxGoats_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void ListBoxSheeps_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void ListBoxCows_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            // Remove dead animals after ticking
            foreach (var animal in Animals.ToList())
            {
                if (animal.IsAlive)
                {
                    animal.Tick(1); 
                    animal.ProduceProduct();
                }
            }
            Animals.RemoveAll(a => !a.IsAlive);
            UpdateAnimalAreas();
        }

        private void MoneyBar_Click(object sender, EventArgs e)
        {
            MessageBox.Show($"Current cash: {Cashbox}");
        }

        private void MilkBar_Click(object sender, EventArgs e)
        {

        }

        private void WoolBar_Click(object sender, EventArgs e)
        {

        }

        private void EggBar_Click(object sender, EventArgs e)
        {

        }

        private void GoatMilkBar_Click(object sender, EventArgs e)
        {

        }
    }

}
