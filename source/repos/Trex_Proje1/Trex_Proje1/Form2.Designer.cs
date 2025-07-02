namespace Trex_Proje1
{
    partial class Form2
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.label1 = new System.Windows.Forms.Label();
            this.AnimalType = new System.Windows.Forms.ListBox();
            this.label2 = new System.Windows.Forms.Label();
            this.Gender = new System.Windows.Forms.ListBox();
            this.label3 = new System.Windows.Forms.Label();
            this.Add = new System.Windows.Forms.Button();
            this.Age = new System.Windows.Forms.TextBox();
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(33, 97);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(48, 16);
            this.label1.TabIndex = 0;
            this.label1.Text = "Animal";
            this.label1.Click += new System.EventHandler(this.label1_Click);
            // 
            // AnimalType
            // 
            this.AnimalType.FormattingEnabled = true;
            this.AnimalType.ItemHeight = 16;
            this.AnimalType.Items.AddRange(new object[] {
            "Cow",
            "Sheep",
            "Chicken",
            "Goat"});
            this.AnimalType.Location = new System.Drawing.Point(105, 97);
            this.AnimalType.Name = "AnimalType";
            this.AnimalType.Size = new System.Drawing.Size(147, 36);
            this.AnimalType.TabIndex = 1;
            this.AnimalType.SelectedIndexChanged += new System.EventHandler(this.AnimalType_SelectedIndexChanged);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(33, 164);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(32, 16);
            this.label2.TabIndex = 0;
            this.label2.Text = "Age";
            this.label2.Click += new System.EventHandler(this.label1_Click);
            // 
            // Gender
            // 
            this.Gender.FormattingEnabled = true;
            this.Gender.ItemHeight = 16;
            this.Gender.Items.AddRange(new object[] {
            "Male",
            "Female"});
            this.Gender.Location = new System.Drawing.Point(105, 226);
            this.Gender.Name = "Gender";
            this.Gender.Size = new System.Drawing.Size(147, 36);
            this.Gender.TabIndex = 1;
            this.Gender.SelectedIndexChanged += new System.EventHandler(this.Gender_SelectedIndexChanged);
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(33, 236);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(52, 16);
            this.label3.TabIndex = 0;
            this.label3.Text = "Gender";
            this.label3.Click += new System.EventHandler(this.label1_Click);
            // 
            // Add
            // 
            this.Add.Location = new System.Drawing.Point(329, 345);
            this.Add.Name = "Add";
            this.Add.Size = new System.Drawing.Size(110, 43);
            this.Add.TabIndex = 4;
            this.Add.Text = "Add";
            this.Add.UseVisualStyleBackColor = true;
            this.Add.Click += new System.EventHandler(this.Add_Click);
            // 
            // Age
            // 
            this.Age.Location = new System.Drawing.Point(105, 164);
            this.Age.Name = "Age";
            this.Age.Size = new System.Drawing.Size(147, 22);
            this.Age.TabIndex = 6;
            this.Age.TextChanged += new System.EventHandler(this.Age_TextChanged);
            // 
            // Form2
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.SystemColors.ActiveCaption;
            this.ClientSize = new System.Drawing.Size(800, 450);
            this.Controls.Add(this.Age);
            this.Controls.Add(this.Add);
            this.Controls.Add(this.Gender);
            this.Controls.Add(this.AnimalType);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Name = "Form2";
            this.Text = "Form2";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.ListBox AnimalType;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.ListBox Gender;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Button Add;
        private System.Windows.Forms.TextBox Age;
    }
}