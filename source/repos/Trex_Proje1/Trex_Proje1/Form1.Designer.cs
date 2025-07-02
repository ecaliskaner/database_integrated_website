namespace Trex_Proje1
{
    partial class Form1
    {
        /// <summary>
        ///Gerekli tasarımcı değişkeni.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        ///Kullanılan tüm kaynakları temizleyin.
        /// </summary>
        ///<param name="disposing">yönetilen kaynaklar dispose edilmeliyse doğru; aksi halde yanlış.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer üretilen kod

        /// <summary>
        /// Tasarımcı desteği için gerekli metot - bu metodun 
        ///içeriğini kod düzenleyici ile değiştirmeyin.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.textBox1 = new System.Windows.Forms.TextBox();
            this.CowArea = new System.Windows.Forms.GroupBox();
            this.ChickenArea = new System.Windows.Forms.GroupBox();
            this.GoatArea = new System.Windows.Forms.GroupBox();
            this.MoneyBar = new System.Windows.Forms.ProgressBar();
            this.button1 = new System.Windows.Forms.Button();
            this.SheepArea = new System.Windows.Forms.GroupBox();
            this.Sell = new System.Windows.Forms.Button();
            this.ListBoxSheeps = new System.Windows.Forms.ListBox();
            this.ListBoxGoats = new System.Windows.Forms.ListBox();
            this.ListBoxChicken = new System.Windows.Forms.ListBox();
            this.ListBoxCows = new System.Windows.Forms.ListBox();
            this.timer1 = new System.Windows.Forms.Timer(this.components);
            this.WoolBar = new System.Windows.Forms.ProgressBar();
            this.MilkBar = new System.Windows.Forms.ProgressBar();
            this.EggBar = new System.Windows.Forms.ProgressBar();
            this.GoatMilkBar = new System.Windows.Forms.ProgressBar();
            this.CowArea.SuspendLayout();
            this.ChickenArea.SuspendLayout();
            this.GoatArea.SuspendLayout();
            this.SheepArea.SuspendLayout();
            this.SuspendLayout();
            // 
            // textBox1
            // 
            this.textBox1.BackColor = System.Drawing.SystemColors.ActiveCaption;
            this.textBox1.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBox1.Font = new System.Drawing.Font("Microsoft Sans Serif", 15F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.textBox1.Location = new System.Drawing.Point(352, 25);
            this.textBox1.Name = "textBox1";
            this.textBox1.Size = new System.Drawing.Size(224, 29);
            this.textBox1.TabIndex = 6;
            this.textBox1.Text = "The Barn Project";
            this.textBox1.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            // 
            // CowArea
            // 
            this.CowArea.BackColor = System.Drawing.Color.GreenYellow;
            this.CowArea.Controls.Add(this.MilkBar);
            this.CowArea.Controls.Add(this.ListBoxCows);
            this.CowArea.Location = new System.Drawing.Point(55, 76);
            this.CowArea.Name = "CowArea";
            this.CowArea.Size = new System.Drawing.Size(298, 186);
            this.CowArea.TabIndex = 7;
            this.CowArea.TabStop = false;
            this.CowArea.Text = "Cows (120$)";
            this.CowArea.Enter += new System.EventHandler(this.CowArea_Enter);
            // 
            // ChickenArea
            // 
            this.ChickenArea.BackColor = System.Drawing.Color.GreenYellow;
            this.ChickenArea.Controls.Add(this.EggBar);
            this.ChickenArea.Controls.Add(this.ListBoxChicken);
            this.ChickenArea.Location = new System.Drawing.Point(55, 292);
            this.ChickenArea.Name = "ChickenArea";
            this.ChickenArea.Size = new System.Drawing.Size(298, 187);
            this.ChickenArea.TabIndex = 9;
            this.ChickenArea.TabStop = false;
            this.ChickenArea.Text = "Chicken(30$)";
            this.ChickenArea.Enter += new System.EventHandler(this.ChickenArea_Enter);
            // 
            // GoatArea
            // 
            this.GoatArea.BackColor = System.Drawing.Color.GreenYellow;
            this.GoatArea.Controls.Add(this.GoatMilkBar);
            this.GoatArea.Controls.Add(this.ListBoxGoats);
            this.GoatArea.Location = new System.Drawing.Point(545, 292);
            this.GoatArea.Name = "GoatArea";
            this.GoatArea.Size = new System.Drawing.Size(298, 187);
            this.GoatArea.TabIndex = 8;
            this.GoatArea.TabStop = false;
            this.GoatArea.Text = "Goat(50$)";
            this.GoatArea.Enter += new System.EventHandler(this.GoatArea_Enter);
            // 
            // MoneyBar
            // 
            this.MoneyBar.AccessibleDescription = "";
            this.MoneyBar.AccessibleName = "";
            this.MoneyBar.Location = new System.Drawing.Point(27, 485);
            this.MoneyBar.Name = "MoneyBar";
            this.MoneyBar.Size = new System.Drawing.Size(847, 23);
            this.MoneyBar.TabIndex = 10;
            this.MoneyBar.Click += new System.EventHandler(this.MoneyBar_Click);
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(415, 239);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(75, 23);
            this.button1.TabIndex = 11;
            this.button1.Text = "Add ";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // SheepArea
            // 
            this.SheepArea.BackColor = System.Drawing.Color.GreenYellow;
            this.SheepArea.Controls.Add(this.WoolBar);
            this.SheepArea.Controls.Add(this.ListBoxSheeps);
            this.SheepArea.Location = new System.Drawing.Point(545, 76);
            this.SheepArea.Name = "SheepArea";
            this.SheepArea.Size = new System.Drawing.Size(298, 186);
            this.SheepArea.TabIndex = 8;
            this.SheepArea.TabStop = false;
            this.SheepArea.Text = "Sheep(80$)";
            this.SheepArea.Enter += new System.EventHandler(this.SheepArea_Enter);
            // 
            // Sell
            // 
            this.Sell.Location = new System.Drawing.Point(415, 292);
            this.Sell.Name = "Sell";
            this.Sell.Size = new System.Drawing.Size(75, 23);
            this.Sell.TabIndex = 15;
            this.Sell.Text = "Sell";
            this.Sell.UseVisualStyleBackColor = true;
            this.Sell.Click += new System.EventHandler(this.Sell_Click);
            // 
            // ListBoxSheeps
            // 
            this.ListBoxSheeps.FormattingEnabled = true;
            this.ListBoxSheeps.ItemHeight = 16;
            this.ListBoxSheeps.Location = new System.Drawing.Point(12, 40);
            this.ListBoxSheeps.Name = "ListBoxSheeps";
            this.ListBoxSheeps.Size = new System.Drawing.Size(266, 132);
            this.ListBoxSheeps.TabIndex = 0;
            this.ListBoxSheeps.SelectedIndexChanged += new System.EventHandler(this.ListBoxSheeps_SelectedIndexChanged);
            // 
            // ListBoxGoats
            // 
            this.ListBoxGoats.FormattingEnabled = true;
            this.ListBoxGoats.ItemHeight = 16;
            this.ListBoxGoats.Location = new System.Drawing.Point(12, 34);
            this.ListBoxGoats.Name = "ListBoxGoats";
            this.ListBoxGoats.Size = new System.Drawing.Size(266, 132);
            this.ListBoxGoats.TabIndex = 0;
            this.ListBoxGoats.SelectedIndexChanged += new System.EventHandler(this.ListBoxGoats_SelectedIndexChanged);
            // 
            // ListBoxChicken
            // 
            this.ListBoxChicken.FormattingEnabled = true;
            this.ListBoxChicken.ItemHeight = 16;
            this.ListBoxChicken.Location = new System.Drawing.Point(14, 34);
            this.ListBoxChicken.Name = "ListBoxChicken";
            this.ListBoxChicken.Size = new System.Drawing.Size(266, 132);
            this.ListBoxChicken.TabIndex = 16;
            this.ListBoxChicken.SelectedIndexChanged += new System.EventHandler(this.ListBoxChicken_SelectedIndexChanged);
            // 
            // ListBoxCows
            // 
            this.ListBoxCows.FormattingEnabled = true;
            this.ListBoxCows.ItemHeight = 16;
            this.ListBoxCows.Location = new System.Drawing.Point(14, 40);
            this.ListBoxCows.Name = "ListBoxCows";
            this.ListBoxCows.Size = new System.Drawing.Size(266, 132);
            this.ListBoxCows.TabIndex = 16;
            this.ListBoxCows.SelectedIndexChanged += new System.EventHandler(this.ListBoxCows_SelectedIndexChanged);
            // 
            // timer1
            // 
            this.timer1.Enabled = true;
            this.timer1.Interval = 1000;
            this.timer1.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // WoolBar
            // 
            this.WoolBar.Location = new System.Drawing.Point(-1, 168);
            this.WoolBar.Name = "WoolBar";
            this.WoolBar.Size = new System.Drawing.Size(300, 18);
            this.WoolBar.TabIndex = 16;
            this.WoolBar.Click += new System.EventHandler(this.WoolBar_Click);
            // 
            // MilkBar
            // 
            this.MilkBar.Location = new System.Drawing.Point(-1, 168);
            this.MilkBar.Name = "MilkBar";
            this.MilkBar.Size = new System.Drawing.Size(299, 18);
            this.MilkBar.TabIndex = 17;
            this.MilkBar.Click += new System.EventHandler(this.MilkBar_Click);
            // 
            // EggBar
            // 
            this.EggBar.Location = new System.Drawing.Point(-1, 166);
            this.EggBar.Name = "EggBar";
            this.EggBar.Size = new System.Drawing.Size(299, 21);
            this.EggBar.TabIndex = 18;
            this.EggBar.Click += new System.EventHandler(this.EggBar_Click);
            // 
            // GoatMilkBar
            // 
            this.GoatMilkBar.Location = new System.Drawing.Point(0, 166);
            this.GoatMilkBar.Name = "GoatMilkBar";
            this.GoatMilkBar.Size = new System.Drawing.Size(299, 21);
            this.GoatMilkBar.TabIndex = 19;
            this.GoatMilkBar.Click += new System.EventHandler(this.GoatMilkBar_Click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.SystemColors.ActiveCaption;
            this.ClientSize = new System.Drawing.Size(886, 512);
            this.Controls.Add(this.Sell);
            this.Controls.Add(this.SheepArea);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.MoneyBar);
            this.Controls.Add(this.GoatArea);
            this.Controls.Add(this.ChickenArea);
            this.Controls.Add(this.CowArea);
            this.Controls.Add(this.textBox1);
            this.Name = "Form1";
            this.Text = "Form1";
            this.CowArea.ResumeLayout(false);
            this.ChickenArea.ResumeLayout(false);
            this.GoatArea.ResumeLayout(false);
            this.SheepArea.ResumeLayout(false);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.TextBox textBox1;
        private System.Windows.Forms.GroupBox CowArea;
        private System.Windows.Forms.GroupBox ChickenArea;
        private System.Windows.Forms.GroupBox GoatArea;
        private System.Windows.Forms.ProgressBar MoneyBar;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.GroupBox SheepArea;
        private System.Windows.Forms.Button Sell;
        private System.Windows.Forms.ListBox ListBoxChicken;
        private System.Windows.Forms.ListBox ListBoxGoats;
        private System.Windows.Forms.ListBox ListBoxSheeps;
        private System.Windows.Forms.ListBox ListBoxCows;
        private System.Windows.Forms.Timer timer1;
        private System.Windows.Forms.ProgressBar MilkBar;
        private System.Windows.Forms.ProgressBar EggBar;
        private System.Windows.Forms.ProgressBar GoatMilkBar;
        private System.Windows.Forms.ProgressBar WoolBar;
    }
}

