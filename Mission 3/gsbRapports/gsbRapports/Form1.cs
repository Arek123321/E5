using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace gsbRapports
{
    public partial class Form1 : Form
    {
        private gsbrapports2021Entities entities;
        public Form1()
        {
            InitializeComponent();
            this.entities = new gsbrapports2021Entities();
        }

       

        private void listeToolStripMenuItem_Click(object sender, EventArgs e)
        {
            MedListe rm = new MedListe(this.entities);
            rm.MdiParent = this;
            rm.Show();
        }

        private void gererMedecinToolStripMenuItem_Click(object sender, EventArgs e)
        {
            MedGerer rm = new MedGerer(this.entities);
            rm.MdiParent = this;
            rm.Show();
        }

        private void majToolStripMenuItem_Click(object sender, EventArgs e)
        {
            DernierRap rm = new DernierRap(this.entities);
            rm.MdiParent = this;
            rm.Show();
        }
    }
}
